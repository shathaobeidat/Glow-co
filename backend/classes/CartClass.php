<?php
require_once __DIR__ . '/ProductClass.php';

// The cart lives in the session: $_SESSION['cart'] = [ product_id => quantity ]
// (guests can fill the cart; they only log in at checkout)
class Cart
{
    public const DELIVERY_FEE = 3.00;

    private function ensure(): void
    {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Returns '' on success, or an error message
    public function add(int $productId, int $qty): string
    {
        $this->ensure();

        $product = (new Product())->getone($productId);
        if (!$product || $product['stock'] < 1) {
            return 'This product is not available.';
        }

        $newQty = ($_SESSION['cart'][$productId] ?? 0) + max(1, $qty);

        if ($newQty > $product['stock']) {
            $_SESSION['cart'][$productId] = $product['stock'];
            return 'Only ' . $product['stock'] . ' of "' . $product['name'] . '" in stock.';
        }

        $_SESSION['cart'][$productId] = $newQty;
        return '';
    }

    // $qtys = [ product_id => new quantity ]. Quantity 0 removes the item.
    public function update($qtys): string
    {
        $this->ensure();
        $error = '';

        if (!is_array($qtys)) {
            return $error;
        }

        $productobj = new Product();
        foreach ($qtys as $productId => $qty) {
            $productId = (int) $productId;
            $qty       = (int) $qty;

            if (!isset($_SESSION['cart'][$productId])) {
                continue;
            }
            if ($qty <= 0) {
                unset($_SESSION['cart'][$productId]);
                continue;
            }

            $product = $productobj->getone($productId);
            if (!$product || $product['stock'] < 1) {
                unset($_SESSION['cart'][$productId]);
            } elseif ($qty > $product['stock']) {
                $_SESSION['cart'][$productId] = $product['stock'];
                $error = 'Only ' . $product['stock'] . ' of "' . $product['name'] . '" in stock.';
            } else {
                $_SESSION['cart'][$productId] = $qty;
            }
        }

        return $error;
    }

    public function remove(int $productId): void
    {
        $this->ensure();
        unset($_SESSION['cart'][$productId]);
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }

    // Cart rows with fresh data from the database
    public function getitems(): array
    {
        $this->ensure();

        $productobj = new Product();
        $items      = [];

        foreach ($_SESSION['cart'] as $productId => $qty) {
            $product = $productobj->getone((int) $productId);

            // product deleted or sold out -> drop it from the cart
            if (!$product || $product['stock'] < 1) {
                unset($_SESSION['cart'][$productId]);
                continue;
            }

            $qty = min((int) $qty, $product['stock']);
            $_SESSION['cart'][$productId] = $qty;

            $items[] = [
                'id'       => $product['id'],
                'name'     => $product['name'],
                'price'    => $product['price'],
                'stock'    => $product['stock'],
                'image'    => $product['image'],
                'qty'      => $qty,
                'subtotal' => $product['price'] * $qty,
            ];
        }

        return $items;
    }

    public function getsummary(array $items): array
    {
        $subtotal = 0.0;
        foreach ($items as $it) {
            $subtotal += $it['subtotal'];
        }
        $delivery = empty($items) ? 0.0 : self::DELIVERY_FEE;

        return [
            'subtotal' => $subtotal,
            'delivery' => $delivery,
            'total'    => $subtotal + $delivery,
        ];
    }
}

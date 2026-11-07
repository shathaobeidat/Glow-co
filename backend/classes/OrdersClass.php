<?php
require_once __DIR__ . '/../config/db.php';

class OrdersClass
{
    public const STATUSES = ['pending', 'processing', 'delivered', 'cancelled'];
    public const PAYMENTS = ['unpaid', 'paid'];

    // CSS class for the coloured status label (works for order status and payment status)
    public static function badgeclass(string $status): string
    {
        $status = strtolower($status);

        if ($status === 'delivered' || $status === 'paid') {
            return 'badge ok';
        }
        if ($status === 'pending' || $status === 'processing' || $status === 'unpaid') {
            return 'badge warn';
        }
        return 'badge';
    }

    // ---------- Customer side ----------

    // Saves the order + its items + the payment row, and reduces the stock.
    // $items and $sum come from the Cart class. Returns the new order id.
    public function create(int $userId, array $form, array $items, array $sum): int
    {
        $dbh = DB_conn::getConnection();
        $dbh->beginTransaction();

        try {
            // 1) make sure there is enough stock (rows are locked until commit)
            $lock = $dbh->prepare("SELECT stock FROM products WHERE id = ? AND deleted_at IS NULL FOR UPDATE");
            foreach ($items as $it) {
                $lock->execute([$it['id']]);
                $stock = $lock->fetchColumn();
                $lock->closeCursor();
                if ($stock === false || (int) $stock < $it['qty']) {
                    throw new RuntimeException('Sorry, "' . $it['name'] . '" is no longer available in the requested quantity.');
                }
            }

            // 2) the order
            $stmt = $dbh->prepare(
                "INSERT INTO orders
                    (user_id, full_name, customer_name, phone, address, city, postal_code, notes,
                     subtotal, delivery_fee, total, status, payment_method)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'cod')"
            );
            $stmt->execute([
                $userId,
                $form['name'],
                $form['name'],
                $form['phone'],
                $form['address'],
                $form['city'],
                $form['zip'] !== '' ? $form['zip'] : null,
                $form['notes'] !== '' ? $form['notes'] : null,
                $sum['subtotal'],
                $sum['delivery'],
                $sum['total'],
            ]);
            $orderId = (int) $dbh->lastInsertId();

            // 3) the items + reduce stock
            $itemStmt = $dbh->prepare(
                "INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity, subtotal)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stockStmt = $dbh->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            foreach ($items as $it) {
                $itemStmt->execute([$orderId, $it['id'], $it['name'], $it['price'], $it['qty'], $it['subtotal']]);
                $stockStmt->execute([$it['qty'], $it['id']]);
            }

            // 4) the payment (cash on delivery -> unpaid until delivered)
            $dbh->prepare("INSERT INTO payments (order_id, method, status, amount) VALUES (?, 'cod', 'unpaid', ?)")
                ->execute([$orderId, $sum['total']]);

            $dbh->commit();
            return $orderId;
        } catch (Throwable $e) {
            $dbh->rollBack();
            throw $e;
        }
    }

    // One order of this customer, with its items (false if not found)
    public function getuserorder(int $id, int $userId)
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT id, status, payment_method, subtotal, delivery_fee, total, created_at,
                    full_name AS shipping_name, phone AS shipping_phone,
                    address AS shipping_address, city AS shipping_city
             FROM orders
             WHERE id = ? AND user_id = ? AND deleted_at IS NULL"
        );
        $stmt->execute([$id, $userId]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            return false;
        }

        $order['id']           = (int) $order['id'];
        $order['subtotal']     = (float) $order['subtotal'];
        $order['delivery_fee'] = (float) $order['delivery_fee'];
        $order['total']        = (float) $order['total'];

        $itemStmt = $dbh->prepare(
            "SELECT product_name AS name, unit_price AS price, quantity AS qty, subtotal
             FROM order_items
             WHERE order_id = ?
             ORDER BY id ASC"
        );
        $itemStmt->execute([$id]);
        $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    // ---------- Admin side ----------

    // Makes the list rows look nice ("Pending", "Unpaid", date only)
    private function maprows(array $rows): array
    {
        foreach ($rows as &$r) {
            $r['id']             = (int) $r['id'];
            $r['total']          = (float) $r['total'];
            $r['status']         = ucfirst((string) $r['status']);
            $r['payment_status'] = ucfirst((string) $r['payment_status']);
            $r['created_at']     = substr((string) $r['created_at'], 0, 10);
        }
        unset($r);

        return $rows;
    }

    public function getall(): array
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT o.id, o.full_name AS shipping_name, o.total, o.status, o.created_at,
                    COALESCE(p.status, 'unpaid') AS payment_status
             FROM orders o
             LEFT JOIN payments p ON p.order_id = o.id
             WHERE o.deleted_at IS NULL
             ORDER BY o.created_at DESC, o.id DESC"
        );
        $stmt->execute();

        return $this->maprows($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getlatest(int $limit = 5): array
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT o.id, o.full_name AS shipping_name, o.total, o.status, o.created_at,
                    COALESCE(p.status, 'unpaid') AS payment_status
             FROM orders o
             LEFT JOIN payments p ON p.order_id = o.id
             WHERE o.deleted_at IS NULL
             ORDER BY o.created_at DESC, o.id DESC
             LIMIT " . (int) $limit
        );
        $stmt->execute();

        return $this->maprows($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // One order for the admin (false if not found)
    public function getorder(int $id)
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT o.id, o.status, o.payment_method, o.subtotal, o.delivery_fee, o.total, o.notes, o.created_at,
                    o.full_name AS shipping_name, o.phone AS shipping_phone,
                    o.address AS shipping_address, o.city AS shipping_city, o.postal_code AS shipping_zip,
                    COALESCE(p.status, 'unpaid') AS payment_status
             FROM orders o
             LEFT JOIN payments p ON p.order_id = o.id
             WHERE o.id = ? AND o.deleted_at IS NULL"
        );
        $stmt->execute([$id]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            return false;
        }

        $order['id']           = (int) $order['id'];
        $order['subtotal']     = (float) $order['subtotal'];
        $order['delivery_fee'] = (float) $order['delivery_fee'];
        $order['total']        = (float) $order['total'];
        $order['shipping_zip'] = (string) ($order['shipping_zip'] ?? '');
        $order['notes']        = (string) ($order['notes'] ?? '');
        return $order;
    }

    public function getorderitems(int $orderId): array
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT product_name AS name, unit_price AS price, quantity, subtotal
             FROM order_items
             WHERE order_id = ?
             ORDER BY id ASC"
        );
        $stmt->execute([$orderId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['price']    = (float) $r['price'];
            $r['quantity'] = (int) $r['quantity'];
            $r['subtotal'] = (float) $r['subtotal'];
        }
        unset($r);

        return $rows;
    }

    // Changes the order status and the payment status.
    // A cancelled order can not be changed again; cancelling gives the stock back.
    public function updateorder(int $id, string $status, string $paymentStatus): bool
    {
        $status        = strtolower($status);
        $paymentStatus = strtolower($paymentStatus);

        if (!in_array($status, self::STATUSES, true) || !in_array($paymentStatus, self::PAYMENTS, true)) {
            return false;
        }

        $dbh = DB_conn::getConnection();
        $dbh->beginTransaction();

        try {
            $select = $dbh->prepare("SELECT status FROM orders WHERE id = ? AND deleted_at IS NULL FOR UPDATE");
            $select->execute([$id]);
            $current = $select->fetchColumn();
            $select->closeCursor();

            if ($current === false || $current === 'cancelled') {
                $dbh->rollBack();
                return false;
            }

            if ($status === 'cancelled') {
                $dbh->prepare(
                    "UPDATE products p
                     JOIN order_items i ON i.product_id = p.id
                     SET p.stock = p.stock + i.quantity
                     WHERE i.order_id = ?"
                )->execute([$id]);
            }

            $dbh->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$status, $id]);

            if ($paymentStatus === 'paid') {
                $dbh->prepare("UPDATE payments SET status = 'paid', paid_at = COALESCE(paid_at, NOW()) WHERE order_id = ?")
                    ->execute([$id]);
            } else {
                $dbh->prepare("UPDATE payments SET status = 'unpaid', paid_at = NULL WHERE order_id = ?")
                    ->execute([$id]);
            }

            $dbh->commit();
            return true;
        } catch (Throwable $e) {
            $dbh->rollBack();
            throw $e;
        }
    }

    // Numbers for the dashboard
    public function getstats(): array
    {
        $dbh = DB_conn::getConnection();

        $revenue = $dbh->query(
            "SELECT COALESCE(SUM(o.total), 0)
             FROM orders o
             JOIN payments p ON p.order_id = o.id
             WHERE o.deleted_at IS NULL AND p.status = 'paid'"
        )->fetchColumn();

        $orders = $dbh->query("SELECT COUNT(*) FROM orders WHERE deleted_at IS NULL")->fetchColumn();
        $products = $dbh->query("SELECT COUNT(*) FROM products WHERE deleted_at IS NULL")->fetchColumn();
        $customers = $dbh->query("SELECT COUNT(*) FROM users WHERE role = 'customer' AND deleted_at IS NULL")->fetchColumn();

        return [
            'revenue'   => (float) $revenue,
            'orders'    => (int) $orders,
            'products'  => (int) $products,
            'customers' => (int) $customers,
        ];
    }
}

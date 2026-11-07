<?php
require_once __DIR__ . '/../config/db.php';

class AdminOrdersClass
{
    public const STATUSES = ['pending', 'processing', 'delivered', 'cancelled'];
    public const PAYMENTS = ['unpaid', 'paid'];

    public function getall(): array
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT id, shipping_name, total, payment_status, status, created_at
             FROM orders
             WHERE deleted_at IS NULL
             ORDER BY created_at DESC, id DESC"
        );
        $stmt->execute();

        return $this->maprows($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getorder(int $id)
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT id, status, payment_method, payment_status, subtotal, delivery_fee, total,
                    shipping_name, shipping_phone, shipping_address, shipping_city, shipping_zip,
                    notes, created_at
             FROM orders
             WHERE id = ? AND deleted_at IS NULL"
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
            "SELECT product_name AS name, price, quantity
             FROM order_items
             WHERE order_id = ?
             ORDER BY id ASC"
        );
        $stmt->execute([$orderId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['price']    = (float) $r['price'];
            $r['quantity'] = (int) $r['quantity'];
            $r['subtotal'] = $r['price'] * $r['quantity'];
        }
        unset($r);

        return $rows;
    }

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

            if ($current === false || strtolower((string) $current) === 'cancelled') {
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

            $dbh->prepare("UPDATE orders SET status = ?, payment_status = ? WHERE id = ?")
                ->execute([$status, $paymentStatus, $id]);

            $dbh->commit();
            return true;
        } catch (Throwable $e) {
            $dbh->rollBack();
            throw $e;
        }
    }

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

    public function getlatest(int $limit = 5): array
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT id, shipping_name, total, payment_status, status, created_at
             FROM orders
             WHERE deleted_at IS NULL
             ORDER BY created_at DESC, id DESC
             LIMIT :lim"
        );
        $stmt->bindValue(':lim', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();

        return $this->maprows($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

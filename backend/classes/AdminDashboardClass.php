<?php
require_once __DIR__ . '/../config/db.php';

class AdminDashboardClass
{
    public function getstats(): array
    {
        $dbh = DB_conn::getConnection();

        $revenue = $dbh->query(
            "SELECT COALESCE(SUM(total), 0) FROM orders
             WHERE deleted_at IS NULL AND payment_status = 'paid'"
        )->fetchColumn();

        $orders = $dbh->query(
            "SELECT COUNT(*) FROM orders WHERE deleted_at IS NULL"
        )->fetchColumn();

        $products = $dbh->query(
            "SELECT COUNT(*) FROM products WHERE deleted_at IS NULL"
        )->fetchColumn();

        $customers = $dbh->query(
            "SELECT COUNT(*) FROM users WHERE role = 'customer' AND deleted_at IS NULL"
        )->fetchColumn();

        return [
            'revenue'   => (float) $revenue,
            'orders'    => (int) $orders,
            'products'  => (int) $products,
            'customers' => (int) $customers,
        ];
    }
}

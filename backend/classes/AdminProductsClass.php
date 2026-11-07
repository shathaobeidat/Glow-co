<?php
require_once __DIR__ . '/../config/db.php';

class AdminProductsClass
{
    public function getall(): array
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT p.id, p.name, p.price, p.stock, p.image, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.deleted_at IS NULL
             ORDER BY p.id DESC"
        );
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['id']            = (int) $r['id'];
            $r['price']         = (float) $r['price'];
            $r['stock']         = (int) $r['stock'];
            $r['image']         = (string) ($r['image'] ?? '');
            $r['category_name'] = (string) ($r['category_name'] ?? '');
        }
        unset($r);

        return $rows;
    }

    public function getone(int $id)
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT id, category_id, name, description, price, stock, image
             FROM products
             WHERE id = ? AND deleted_at IS NULL"
        );
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        $row['id']          = (int) $row['id'];
        $row['category_id'] = (int) ($row['category_id'] ?? 0);
        $row['description'] = (string) ($row['description'] ?? '');
        $row['price']       = (float) $row['price'];
        $row['stock']       = (int) $row['stock'];
        $row['image']       = (string) ($row['image'] ?? '');
        return $row;
    }

    public function create(int $categoryId, string $name, string $description, float $price, int $stock, string $image): int
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "INSERT INTO products (category_id, name, description, price, stock, image)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$categoryId, $name, $description, $price, $stock, $image !== '' ? $image : null]);
        return (int) $dbh->lastInsertId();
    }

    public function update(int $id, int $categoryId, string $name, string $description, float $price, int $stock, string $image = ''): bool
    {
        $dbh    = DB_conn::getConnection();
        $sql    = "UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, stock = ?";
        $params = [$categoryId, $name, $description, $price, $stock];

        if ($image !== '') {
            $sql    .= ", image = ?";
            $params[] = $image;
        }
        $sql     .= " WHERE id = ? AND deleted_at IS NULL";
        $params[] = $id;

        return $dbh->prepare($sql)->execute($params);
    }

    public function delete(int $id): bool
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare("UPDATE products SET deleted_at = NOW() WHERE id = ? AND deleted_at IS NULL");
        return $stmt->execute([$id]);
    }
}

<?php
require_once __DIR__ . '/../config/db.php';

class Product
{
    // Makes sure every value has the right type (so the pages never get NULL)
    private function maprow(array $r): array
    {
        $r['id']            = (int) $r['id'];
        $r['category_id']   = (int) ($r['category_id'] ?? 0);
        $r['description']   = (string) ($r['description'] ?? '');
        $r['price']         = (float) $r['price'];
        $r['stock']         = (int) $r['stock'];
        $r['image']         = (string) ($r['image'] ?? '');
        $r['category_name'] = (string) ($r['category_name'] ?? '');
        return $r;
    }

    // List of products. Optional: search by name, filter by category, pagination.
    // $limit = 0 means "no limit" (used by the admin page).
    public function getproducts(string $search = '', int $categoryId = 0, int $limit = 0, int $offset = 0): array
    {
        $dbh = DB_conn::getConnection();

        $sql = "SELECT p.id, p.category_id, p.name, p.description, p.price, p.stock, p.image,
                       c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id
                WHERE p.deleted_at IS NULL";
        $params = [];

        if ($search !== '') {
            $sql     .= " AND p.name LIKE ?";
            $params[] = '%' . $search . '%';
        }
        if ($categoryId > 0) {
            $sql     .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        $sql .= " ORDER BY p.created_at DESC, p.id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
        }

        $stmt = $dbh->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r = $this->maprow($r);
        }
        unset($r);

        return $rows;
    }

    // How many products match the search/filter (for pagination)
    public function countproducts(string $search = '', int $categoryId = 0): int
    {
        $dbh = DB_conn::getConnection();

        $sql    = "SELECT COUNT(*) FROM products p WHERE p.deleted_at IS NULL";
        $params = [];

        if ($search !== '') {
            $sql     .= " AND p.name LIKE ?";
            $params[] = '%' . $search . '%';
        }
        if ($categoryId > 0) {
            $sql     .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        $stmt = $dbh->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    // One product by id (false if not found)
    public function getone(int $id)
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT p.id, p.category_id, p.name, p.description, p.price, p.stock, p.image,
                    c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.id = ? AND p.deleted_at IS NULL"
        );
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        return $this->maprow($row);
    }

    // "You may also like": other products from the same category
    public function getrelated(int $id, int $categoryId, int $limit = 4): array
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT p.id, p.category_id, p.name, p.description, p.price, p.stock, p.image,
                    c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.deleted_at IS NULL AND p.category_id = ? AND p.id <> ?
             ORDER BY p.id DESC
             LIMIT " . (int) $limit
        );
        $stmt->execute([$categoryId, $id]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r = $this->maprow($r);
        }
        unset($r);

        return $rows;
    }

    // ---------- Admin ----------

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

    // $image = '' means "keep the old image"
    public function update(int $id, int $categoryId, string $name, string $description, float $price, int $stock, string $image = ''): bool
    {
        $dbh    = DB_conn::getConnection();
        $sql    = "UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, stock = ?";
        $params = [$categoryId, $name, $description, $price, $stock];

        if ($image !== '') {
            $sql     .= ", image = ?";
            $params[] = $image;
        }
        $sql     .= " WHERE id = ? AND deleted_at IS NULL";
        $params[] = $id;

        return $dbh->prepare($sql)->execute($params);
    }

    // Soft delete (we only fill deleted_at)
    public function delete(int $id): bool
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare("UPDATE products SET deleted_at = NOW() WHERE id = ? AND deleted_at IS NULL");
        return $stmt->execute([$id]);
    }
}

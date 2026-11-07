<?php
require_once __DIR__ . '/../config/db.php';

class ProductsClass
{
    public function getcategories(): array
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT id, name
             FROM categories
             WHERE deleted_at IS NULL
             ORDER BY name ASC"
        );
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['id'] = (int) $r['id'];
        }
        unset($r);

        return $rows;
    }

    public function getproducts(string $search = '', int $categoryId = 0): array
    {
        $dbh = DB_conn::getConnection();

        $sql = "SELECT p.id, p.name, p.price, p.stock, p.image,
                       c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id
                WHERE p.deleted_at IS NULL";
        $params = [];

        if ($search !== '') {
            $sql .= " AND p.name LIKE :q";
            $params[':q'] = '%' . $search . '%';
        }
        if ($categoryId > 0) {
            $sql .= " AND p.category_id = :cid";
            $params[':cid'] = $categoryId;
        }

        $sql .= " ORDER BY p.created_at DESC, p.id DESC";

        $stmt = $dbh->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, $key === ':cid' ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
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
}
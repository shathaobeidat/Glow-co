<?php
require_once __DIR__ . '/../config/db.php';

class AdminCategoriesClass
{
    public function getall(): array
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT id, name, description, image
             FROM categories
             WHERE deleted_at IS NULL
             ORDER BY name ASC"
        );
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['id']          = (int) $r['id'];
            $r['description'] = (string) ($r['description'] ?? '');
            $r['image']       = (string) ($r['image'] ?? '');
        }
        unset($r);

        return $rows;
    }

    public function getone(int $id)
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare(
            "SELECT id, name, description, image
             FROM categories
             WHERE id = ? AND deleted_at IS NULL"
        );
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        $row['id']          = (int) $row['id'];
        $row['description'] = (string) ($row['description'] ?? '');
        $row['image']       = (string) ($row['image'] ?? '');
        return $row;
    }

    public function create(string $name, string $description, string $image): int
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare("INSERT INTO categories (name, description, image) VALUES (?, ?, ?)");
        $stmt->execute([
            $name,
            $description !== '' ? $description : null,
            $image !== '' ? $image : null,
        ]);
        return (int) $dbh->lastInsertId();
    }

    public function update(int $id, string $name, string $description, string $image = ''): bool
    {
        $dbh    = DB_conn::getConnection();
        $sql    = "UPDATE categories SET name = ?, description = ?";
        $params = [$name, $description !== '' ? $description : null];

        if ($image !== '') {
            $sql    .= ", image = ?";
            $params[] = $image;
        }
        $sql     .= " WHERE id = ? AND deleted_at IS NULL";
        $params[] = $id;

        return $dbh->prepare($sql)->execute($params);
    }

    public function countproducts(int $id): int
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare("SELECT COUNT(*) FROM products WHERE category_id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public function delete(int $id): bool
    {
        $dbh  = DB_conn::getConnection();
        $stmt = $dbh->prepare("UPDATE categories SET deleted_at = NOW() WHERE id = ? AND deleted_at IS NULL");
        return $stmt->execute([$id]);
    }
}

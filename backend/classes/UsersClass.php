<?php
require_once __DIR__ .'/../config/db.php';

class UsersClass
{

    public function register($name, $email, $password, $role) {
        $dbh = DB_conn::getConnection();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $inst = $dbh->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        return $inst->execute([$name, $email, $hashedPassword, $role]);
    }

    public function login($email, $password) {
        try {
            $dbh = DB_conn::getConnection();
            $stmt = $dbh->prepare(
                "SELECT * FROM users
                 WHERE email = ? AND deleted_at IS NULL"
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password_hash'])) {
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function getprofile(int $id)
{
    $dbh  = DB_conn::getConnection();
    $stmt = $dbh->prepare(
        "SELECT name, email, created_at
         FROM users
         WHERE id = ? AND deleted_at IS NULL"
    );
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC); 
}
public function updateprofile(int $id, string $name, string $email): bool
{
    $dbh  = DB_conn::getConnection();
    $stmt = $dbh->prepare(
        "UPDATE users SET name = ?, email = ?
         WHERE id = ? AND deleted_at IS NULL"
    );
    return $stmt->execute([$name, $email, $id]);
}

public function emailexists(string $email, int $excludeId = 0): bool
{
    $dbh  = DB_conn::getConnection();
    $stmt = $dbh->prepare("SELECT 1 FROM users WHERE email = ? AND id <> ? LIMIT 1");
    $stmt->execute([$email, $excludeId]);
    return (bool) $stmt->fetchColumn();
}
public function getorders(int $userId, int $limit = 10): array
{
    $dbh  = DB_conn::getConnection();
    $stmt = $dbh->prepare(
        "SELECT id, status, total, created_at
         FROM orders
         WHERE user_id = :uid AND deleted_at IS NULL
         ORDER BY created_at DESC, id DESC
         LIMIT :lim"
    );
    $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':lim', max(1, $limit), PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) {
        $r['id']         = (int)$r['id'];
        $r['total']      = (float)$r['total'];
        $r['status']     = ucfirst((string)$r['status']);
        $r['created_at'] = substr((string)$r['created_at'], 0, 10);
    }
    unset($r);

    return $rows;
}
    }

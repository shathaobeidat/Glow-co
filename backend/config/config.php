<?php
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}



$DB_connValues = [
    'host' => 'localhost',
    'dbname' => 'glow_co',
    'username' => 'root',
    'password' => ''
];

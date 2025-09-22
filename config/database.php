<?php

declare(strict_types=1);
/**
 * Returns a shared PDO connection to the MySQL database.
 *
 * The connection credentials can be overridden via environment variables:
 * DB_HOST, DB_NAME, DB_USER, DB_PASSWORD.
 */
function getDbConnection(): ?\PDO
{
    static $pdo;

    if ($pdo === false) {
        return null;
    }

    if ($pdo instanceof \PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: 'localhost';
    $dbName = getenv('DB_NAME') ?: 'u331221487_irohaDB';
    $user = getenv('DB_USER') ?: 'u331221487_mmasabi';
    $password = getenv('DB_PASSWORD') ?: 'Musabi@0594332524';

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $dbName);
    $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new \PDO($dsn, $user, $password, $options);
    } catch (\PDOException $exception) {
        error_log('Database connection failed: ' . $exception->getMessage());
        $pdo = false;
        return null;
    }

    return $pdo;
}

<?php
namespace Cartrack\Db;

class Database
{
    static $instance;

    public static function connect(array $config)
    {
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['db']};";
        self::$instance = new \PDO($dsn, $config['user'], $config['pass'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
    }

    static function getInstance(): \PDO
    {
        return self::$instance;
    }

    public static function query(string $sql, array $parameters): \PDOStatement
    {
        $statement = self::$instance->prepare($sql);
        $statement->execute($parameters);
        return $statement;
    }
}
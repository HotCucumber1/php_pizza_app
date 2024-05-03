<?php
namespace App\Connection;

class ConnectionProvider
{
    static public function getConnectionParams(): array
    {
        return [
            'dsn' => $_ENV['DSN'],
            'username' => $_ENV['USERNAME'],
            'password' => $_ENV['PASSWORD']
        ];
    }

    static function connectDatabase(array $params): \PDO
    {
        return new \PDO($params['dsn'], $params['username'], $params['password']);
    }
}
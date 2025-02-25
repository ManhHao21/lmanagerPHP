<?php
namespace App\Config;

use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

class Database
{
    protected $connection;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Cấu hình database
        $this->connection = DriverManager::getConnection([
            'dbname'   => $_ENV['DB_NAME'],
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'host'     => $_ENV['DB_HOST'],
            'driver'   => $_ENV['DB_DRIVER'],
        ]);
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

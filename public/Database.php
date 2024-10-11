<?php

class Database {
    private static $instance = null;
    private $pdo;

    // Конструктор должен быть приватным для предотвращения создания экземпляра извне
    private function __construct() {
        $host = 'mysql-8.2';
        $dbname = 'test_db';
        $username = 'root';
        $password = '';

        try {
            $this->pdo = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            $this->errorResponse('Ошибка подключения к базе данных: ' . $e->getMessage());
        }
    }

    // Метод для получения экземпляра базы данных (Singleton)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function errorResponse($message) {
        echo json_encode(['error' => $message]);
        exit;
    }
}

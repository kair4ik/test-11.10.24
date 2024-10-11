<?php

class ProductService {
    private $database;

    public function __construct() {
        $this->database = Database::getInstance()->getConnection();
    }

    public function addProducts($products) {
        $stmtCheck = $this->database->prepare("SELECT COUNT(*) FROM products WHERE title = :title");
        $stmtInsert = $this->database->prepare("INSERT INTO products (title, price) VALUES (:title, :price)");

        $addedCount = 0; // Счетчик добавленных товаров

        foreach ($products as $product) {
            // Проверяем существование товара
            $stmtCheck->execute(['title' => $product['title']]);
            $exists = $stmtCheck->fetchColumn();

            if ($exists) {
                // Товар уже существует, можем пропустить его добавление
                continue; // Или обновить цену, если это необходимо
            }

            // Добавляем товар, если он не существует
            $stmtInsert->execute([
                'title' => $product['title'],
                'price' => $product['price']
            ]);

            $addedCount++; // Увеличиваем счетчик добавленных товаров
        }

        return $addedCount; // Возвращаем количество добавленных товаров
    }

}

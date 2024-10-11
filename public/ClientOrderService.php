<?php

class ClientOrderService {
    private $database;

    public function __construct() {
        $this->database = Database::getInstance()->getConnection();
    }

    public function getClientOrders($client_id) {
        $stmt = $this->database->prepare("
            SELECT 
                u.id AS client_id, 
                CONCAT(u.first_name, ' ', u.second_name) AS full_name, 
                p.title AS product_title, 
                p.price AS product_price, 
                o.created_at AS order_date
            FROM user AS u
            JOIN user_order AS o ON u.id = o.user_id
            JOIN products AS p ON o.product_id = p.id
            WHERE u.id = :client_id
            ORDER BY p.title ASC, p.price DESC
        ");
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetchAll();
    }

    public function formatClientData($orders) {
        if (empty($orders)) {
            return ['error' => 'Клиент не найден или нет заказов'];
        }

        $clientData = [
            'client_id' => $orders[0]['client_id'],
            'full_name' => $orders[0]['full_name'],
            'orders' => []
        ];

        foreach ($orders as $row) {
            $clientData['orders'][] = [
                'product_title' => $row['product_title'],
                'product_price' => $row['product_price'],
                'order_date' => $row['order_date']
            ];
        }

        return $clientData;
    }
}

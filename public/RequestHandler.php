<?php

class RequestHandler {
    private $clientOrderService;
    private $productService;

    public function __construct() {
        $this->clientOrderService = new ClientOrderService();
        $this->productService = new ProductService();
    }

    public function handleRequest() {
        header('Content-Type: application/json');
        if (isset($_GET['id'])) {
            $client_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($client_id === false  || $client_id <= 0) {
                Database::getInstance()->errorResponse('Неверный ID клиента');
            }

            $orders = $this->clientOrderService->getClientOrders($client_id);
            $clientData = $this->clientOrderService->formatClientData($orders);
            echo json_encode($clientData);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputData = file_get_contents("php://input");
            $products = json_decode($inputData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['error' => 'Invalid JSON']);
                exit;
            }

            if (!is_array($products) || empty($products)) {
                echo json_encode(['error' => 'Нет предоставленных продуктов']);
                exit;
            }

            $addedCount = $this->productService->addProducts($products);
            echo json_encode(['success' => true, 'addedCount' => $addedCount]);
        } else {
            echo json_encode(['error' => 'Неверный метод запроса']);
        }
    }
}

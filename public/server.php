<?php
require_once 'Database.php';
require_once 'ClientOrderService.php';
require_once 'ProductService.php';
require_once 'RequestHandler.php';

$requestHandler = new RequestHandler();
$requestHandler->handleRequest();
?>

<?php
require_once "config/db.php";

$shade = isset($_GET['shade']) ? $_GET['shade'] : '';

if($shade){
    $stmt = $conn->prepare("SELECT * FROM products WHERE shade = ?");
    $stmt->execute([$shade]);
} else {
    $stmt = $conn->query("SELECT * FROM products");
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($products);

<?php
session_start();
require_once "config/db.php"; 

$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'];

$stmt = $conn->prepare("DELETE FROM cart WHERE id=?");
$stmt->execute([$cart_id]);

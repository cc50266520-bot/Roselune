<?php
session_start();
require_once "config/db.php"; 

$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'];
$qty = $data['qty'];

$stmt = $conn->prepare("UPDATE cart SET qty=? WHERE id=?");
$stmt->execute([$qty, $cart_id]);

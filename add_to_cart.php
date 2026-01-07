<?php
require_once("config/db.php");
require_once("config/auth.php");
require_login();

$user_id = (int)$_SESSION["user_id"];
$product_id = (int)($_POST["product_id"] ?? 0);
$qty = (int)($_POST["qty"] ?? 1);
if ($qty < 1) $qty = 1;

if ($product_id <= 0) {
  header("Location: products.php");
  exit();
}
$stmt = $conn->prepare("SELECT id, qty FROM cart WHERE user_id=? AND product_id=? LIMIT 1");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
  $row = $res->fetch_assoc();
  $newQty = (int)$row["qty"] + $qty;

  $up = $conn->prepare("UPDATE cart SET qty=? WHERE id=? AND user_id=?");
  $cid = (int)$row["id"];
  $up->bind_param("iii", $newQty, $cid, $user_id);
  $up->execute();
  $up->close();
} else {
  $ins = $conn->prepare("INSERT INTO cart (user_id, product_id, qty) VALUES (?,?,?)");
  $ins->bind_param("iii", $user_id, $product_id, $qty);
  $ins->execute();
  $ins->close();
}

$stmt->close();
header("Location: cart.php");
exit();

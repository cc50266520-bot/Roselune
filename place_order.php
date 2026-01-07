<?php
require_once("config/db.php");
require_once("config/auth.php");
require_once("config/helpers.php");
require_login();

$user_id = (int)($_SESSION["user_id"] ?? 0);
if ($user_id <= 0) { header("Location: auth/login.php"); exit(); }

$address = trim($_SESSION["checkout_address"] ?? "");
$phone   = trim($_SESSION["checkout_phone"] ?? "");
$payment = trim($_SESSION["checkout_payment"] ?? "cash");
$notes   = trim($_SESSION["checkout_notes"] ?? "");


if ($address === "" || $phone === "") {
  header("Location: checkout.php");
  exit();
}
$stmt = $conn->prepare("
  SELECT c.product_id, c.qty, p.name, p.price, p.image
  FROM cart c
  JOIN products p ON p.id = c.product_id
  WHERE c.user_id=?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
$total = 0.0;

while($row = $res->fetch_assoc()){
  $row["qty"] = (int)$row["qty"];
  $row["price"] = (float)$row["price"];
  $row["subtotal"] = $row["qty"] * $row["price"];
  $total += $row["subtotal"];
  $items[] = $row;
}
$stmt->close();

if (count($items) === 0) {
  header("Location: cart.php");
  exit();
}

if (!in_array($payment, ["cash", "card"])) $payment = "cash";

$conn->begin_transaction();

try {
  $status = "pending";

  $ins = $conn->prepare("
    INSERT INTO orders (user_id, total, status, address, phone, payment_method)
    VALUES (?,?,?,?,?,?)
  ");
  $ins->bind_param("idssss", $user_id, $total, $status, $address, $phone, $payment);

  if (!$ins->execute()) {
    throw new Exception("Failed to create order.");
  }
  $order_id = $ins->insert_id;
  $ins->close();

 
  $itemStmt = $conn->prepare("
    INSERT INTO order_items (order_id, product_id, qty, price)
    VALUES (?,?,?,?)
  ");

  foreach($items as $it){
    $pid = (int)$it["product_id"];
    $qty = (int)$it["qty"];
    $price = (float)$it["price"];
    $itemStmt->bind_param("iiid", $order_id, $pid, $qty, $price);
    if (!$itemStmt->execute()) {
      throw new Exception("Failed to insert order item.");
    }
  }
  $itemStmt->close();


  $del = $conn->prepare("DELETE FROM cart WHERE user_id=?");
  $del->bind_param("i", $user_id);
  $del->execute();
  $del->close();


  unset($_SESSION["checkout_address"], $_SESSION["checkout_phone"], $_SESSION["checkout_payment"], $_SESSION["checkout_notes"]);

  $conn->commit();

} catch (Exception $e) {
  $conn->rollback();
  die("Order failed: " . $e->getMessage());
}

$paymentText = ($payment === "card") ? "Credit / Debit Card (" : "Cash on Delivery";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Order Confirmed – Rosélune</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .wrap{
      max-width: 1050px;
      margin: 26px auto 60px auto;
      padding: 0 16px;
    }
    .success-hero{
      background: linear-gradient(135deg, #f7d4e8, #ffe6f2);
      border-radius: 22px;
      padding: 26px;
      box-shadow: 0 18px 40px rgba(0,0,0,.08);
      text-align:center;
    }
    .success-hero h1{
      margin:0;
      font-family: Georgia, serif;
      font-size: 46px;
      color: #b03a6b;
      letter-spacing: 2px;
    }
    .success-hero p{
      margin:10px 0 0 0;
      color:#555;
      font-size:16px;
      line-height:1.7;
    }
    .grid{
      display:grid;
      grid-template-columns: 1.1fr .9fr;
      gap:18px;
      margin-top:18px;
    }
    @media(max-width:900px){ .grid{ grid-template-columns:1fr; } }

    .box{
      background:#fff;
      border-radius:22px;
      padding:20px;
      box-shadow: 0 18px 40px rgba(0,0,0,.08);
    }
    .title{
      margin:0 0 12px 0;
      font-size:20px;
      font-weight:900;
      color:#7a2a4a;
    }
    .kv{
      display:grid;
      gap:10px;
      margin-top: 8px;
    }
    .kv div{
      padding:12px 14px;
      border:1px solid #f1e6ea;
      border-radius:16px;
      color:#3c1a29;
      background:#fff;
    }
    .kv b{ color:#7a2a4a; }

    .item{
      display:flex;
      gap:12px;
      align-items:center;
      padding:10px 0;
      border-bottom:1px solid #f1e6ea;
    }
    .item img{
      width:90px;
      height:70px;
      border-radius:16px;
      object-fit:cover;
      background:#faf6f7;
    }
    .name{ font-weight:900; color:#2b0f1d; }
    .muted2{ color:#666; font-size:13px; }
    .money{ font-weight:900; color:#2b0f1d; }

    .tot{
      display:flex;
      justify-content:space-between;
      margin-top:14px;
      font-weight:900;
      font-size:18px;
      color:#b03a6b;
    }

    .actions{
      display:flex;
      gap:12px;
      flex-wrap:wrap;
      margin-top:14px;
    }
    .btnx{
      text-decoration:none;
      padding:12px 16px;
      border-radius:16px;
      font-weight:900;
      border:1px solid #eadfe3;
      background:#fff;
      color:#7a2a4a;
    }
    .btnx.primary{
      background:#ff5da2;
      color:#fff;
      border:none;
      box-shadow: 0 16px 30px rgba(255,93,162,.28);
    }
  </style>
</head>
<body>

<?php navbar(""); ?>

<div class="wrap">

  <div class="success-hero">
    <h1>Rosélune ✨ Order Confirmed</h1>
    <p>
      Thank you for your purchase! Your order has been placed successfully.<br>
      <b>Order ID:</b> #<?= (int)$order_id ?> • <b>Status:</b> Pending
    </p>
  </div>

  <div class="grid">

    <div class="box">
      <h2 class="title">Order Items</h2>

      <?php foreach($items as $it): ?>
        <div class="item">
          <img src="<?= url(e($it["image"])) ?>" alt="">
          <div style="flex:1;">
            <div class="name"><?= e($it["name"]) ?></div>
            <div class="muted2">Qty: <?= (int)$it["qty"] ?> • $<?= number_format((float)$it["price"],2) ?></div>
          </div>
          <div class="money">$<?= number_format((float)$it["subtotal"],2) ?></div>
        </div>
      <?php endforeach; ?>

      <div class="tot">
        <span>Total</span>
        <span>$<?= number_format((float)$total,2) ?></span>
      </div>

      <div class="actions">
        <a class="btnx primary" href="<?= url('products.php') ?>">Continue Shopping</a>
        <a class="btnx" href="<?= url('index.php') ?>">Back Home</a>
      </div>
    </div>

    <div class="box">
      <h2 class="title">Delivery & Payment</h2>

      <div class="kv">
        <div><b>Delivery Address:</b><br><?= e($address) ?></div>
        <div><b>Phone:</b> <?= e($phone) ?></div>
        <div><b>Payment Method:</b> <?= e($paymentText) ?></div>
        <?php if($notes !== ""): ?>
          <div><b>Notes:</b><br><?= e($notes) ?></div>
        <?php else: ?>
          <div><b>Notes:</b> —</div>
        <?php endif; ?>
      </div>

      <div style="margin-top:14px;color:#666;line-height:1.7;">
      
      </div>
    </div>

  </div>
</div>

</body>
</html>

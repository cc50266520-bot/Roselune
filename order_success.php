<?php
require_once("config/db.php");
require_once("config/auth.php");
require_once("config/helpers.php");
require_login();

$order_id = (int)($_GET["id"] ?? 0);
if ($order_id <= 0) { header("Location: index.php"); exit(); }

$user_id = (int)$_SESSION["user_id"];

$stmt = $conn->prepare("SELECT id,total,status,created_at FROM orders WHERE id=? AND user_id=? LIMIT 1");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) { header("Location: index.php"); exit(); }
$order = $res->fetch_assoc();
$stmt->close();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Order Confirmation - Roselune</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php navbar(""); ?>

<div class="container">
  <div class="section-card" style="background:#fff;text-align:center;padding:34px;">
    <h2 style="font-family:Georgia,serif;color:var(--brand);font-size:46px;margin:0;">✅ Order Confirmed</h2>
    <p class="muted" style="font-size:16px;margin-top:10px;">
      Thank you, <b><?= e($_SESSION["name"] ?? "Customer") ?></b> — your order has been placed successfully.
    </p>

    <div style="margin:18px auto;max-width:520px;background:#faf6f7;border-radius:16px;padding:16px;text-align:left;">
      <div style="display:flex;justify-content:space-between;margin:6px 0;">
        <span class="muted">Order ID</span>
        <b>#<?= (int)$order["id"] ?></b>
      </div>
      <div style="display:flex;justify-content:space-between;margin:6px 0;">
        <span class="muted">Total</span>
        <b>$<?= number_format((float)$order["total"],2) ?></b>
      </div>
      <div style="display:flex;justify-content:space-between;margin:6px 0;">
        <span class="muted">Status</span>
        <b><?= e($order["status"]) ?></b>
      </div>
      <div style="display:flex;justify-content:space-between;margin:6px 0;">
        <span class="muted">Date</span>
        <b><?= e($order["created_at"]) ?></b>
      </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:center;margin-top:18px;flex-wrap:wrap;">
      <a class="btn primary" href="products.php">Continue Shopping</a>
      <a class="small-btn" href="index.php">Back Home</a>
    </div>
  </div>
</div>

</body>
</html>

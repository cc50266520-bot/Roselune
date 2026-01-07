<?php
require_once("../config/db.php");
require_once("../config/auth.php");
require_once("../config/helpers.php");
require_admin();

$order_id = (int)($_GET["id"] ?? 0);
if ($order_id <= 0) { header("Location: orders.php"); exit(); }

$stmt = $conn->prepare("
  SELECT o.id, o.total, o.status, o.created_at, u.name, u.email
  FROM orders o
  LEFT JOIN users u ON u.id = o.user_id
  WHERE o.id=? LIMIT 1
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$or = $stmt->get_result();
if ($or->num_rows !== 1) { header("Location: orders.php"); exit(); }
$order = $or->fetch_assoc();
$stmt->close();

$items = $conn->prepare("
  SELECT oi.qty, oi.price, p.name, p.image
  FROM order_items oi
  LEFT JOIN products p ON p.id = oi.product_id
  WHERE oi.order_id=?
");
$items->bind_param("i", $order_id);
$items->execute();
$list = $items->get_result();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Order #<?= (int)$order_id ?> - Admin</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="main-header">
  <div class="logo">Admin</div>
  <nav>
    <a href="<?= url('admin/orders.php') ?>" class="active">Orders</a>
    <a href="<?= url('admin/add_product.php') ?>">Products</a>
    <a href="<?= url('admin/index.php') ?>">Dashboard</a>
    <a href="<?= url('index.php') ?>">Back to site</a>
    <a class="btn primary" href="<?= url('auth/logout.php') ?>">Logout</a>
  </nav>
</header>

<div class="container">

  <div class="section-card" style="background:#fff;">
    <h2 style="margin:0;">Order #<?= (int)$order["id"] ?></h2>
    <p class="muted">Customer: <b><?= e($order["name"]) ?></b> (<?= e($order["email"]) ?>)</p>
    <p class="muted">Status: <b><?= e($order["status"]) ?></b> • Date: <?= e($order["created_at"]) ?></p>
    <p style="font-weight:900;font-size:18px;">Total: $<?= number_format((float)$order["total"],2) ?></p>
  </div>

  <div class="section-card" style="background:#fff;">
    <h2 style="margin:0 0 10px 0;">Items</h2>

    <?php while($it=$list->fetch_assoc()): ?>
      <div style="display:flex;gap:14px;align-items:center;border-bottom:1px solid #eee;padding:12px 0;">
        <img src="<?= url(e($it["image"] ?? "assets/images/placeholder.jpg")) ?>"
             style="width:110px;height:90px;object-fit:contain;border-radius:14px;background:#faf6f7;">
        <div style="flex:1;">
          <div style="font-weight:900;"><?= e($it["name"] ?? "Product deleted") ?></div>
          <div class="muted">Qty: <?= (int)$it["qty"] ?> • $<?= number_format((float)$it["price"],2) ?></div>
        </div>
      </div>
    <?php endwhile; ?>

    <div style="margin-top:14px;">
      <a class="small-btn" href="<?= url('admin/orders.php') ?>">Back to Orders</a>
    </div>

  </div>

</div>

</body>
</html>
<?php $items->close(); ?>

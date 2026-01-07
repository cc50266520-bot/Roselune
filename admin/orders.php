<?php
require_once("../config/db.php");
require_once("../config/auth.php");
require_once("../config/helpers.php");
require_admin();

$res = $conn->query("
  SELECT o.id, o.total, o.status, o.created_at, u.name, u.email
  FROM orders o
  LEFT JOIN users u ON u.id = o.user_id
  ORDER BY o.id DESC
");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Orders - Admin</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="main-header">
  <div class="logo">Admin</div>
  <nav>
    <a href="<?= url('admin/index.php') ?>">Dashboard</a>
    <a href="<?= url('admin/add_product.php') ?>">Products</a>
    <a href="<?= url('admin/orders.php') ?>" class="active">Orders</a>
    <a href="<?= url('index.php') ?>">Back to site</a>
    <a class="btn primary" href="<?= url('auth/logout.php') ?>">Logout</a>
  </nav>
</header>

<div class="container">
  <div class="section-card" style="background:#fff;">
    <h2 style="margin:0 0 10px 0;">Orders</h2>

    <div style="overflow:auto;">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="text-align:left;border-bottom:2px solid #eee;">
            <th style="padding:10px;">Order ID</th>
            <th style="padding:10px;">Customer</th>
            <th style="padding:10px;">Total</th>
            <th style="padding:10px;">Status</th>
            <th style="padding:10px;">Date</th>
            <th style="padding:10px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($o=$res->fetch_assoc()): ?>
          <tr style="border-bottom:1px solid #eee;">
            <td style="padding:10px;">#<?= (int)$o["id"] ?></td>
            <td style="padding:10px;">
              <?= e($o["name"] ?? "Unknown") ?><br>
              <span class="muted"><?= e($o["email"] ?? "") ?></span>
            </td>
            <td style="padding:10px;">$<?= number_format((float)$o["total"],2) ?></td>
            <td style="padding:10px;"><?= e($o["status"]) ?></td>
            <td style="padding:10px;"><?= e($o["created_at"]) ?></td>
            <td style="padding:10px;">
              <a class="small-btn" href="<?= url('admin/order_details.php?id='.(int)$o["id"]) ?>">View</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

</body>
</html>

<?php
require_once("../config/db.php");
require_once("../config/auth.php");
require_once("../config/helpers.php");
require_admin();

$products = $conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()["c"];
$orders   = $conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()["c"];
$users    = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()["c"];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard - Roselune</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="main-header">
  <div class="logo"><a href="<?= url('admin/index.php') ?>" style="color:inherit;text-decoration:none;">Admin</a></div>
  <nav>
    <a href="<?= url('admin/index.php') ?>" class="active">Dashboard</a>
    <a href="<?= url('admin/add_product.php') ?>">Add Product</a>
    <a href="<?= url('admin/orders.php') ?>">Orders</a>
    <a href="<?= url('index.php') ?>">Back to site</a>
    <a class="btn primary" href="<?= url('auth/logout.php') ?>">Logout</a>
  </nav>
</header>

<div class="container">
  <div class="section-card" style="background:#fff;">
    <h2 style="margin:0 0 10px 0;">Roselune Admin Dashboard</h2>
    <p class="muted">Welcome, <?= e($_SESSION["name"] ?? "Admin") ?>.</p>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-top:16px;">
      <div class="card">
        <h3>Products</h3>
        <div style="font-size:34px;font-weight:900;"><?= (int)$products ?></div>
        <a class="small-btn" href="<?= url('admin/add_product.php') ?>">Manage Products</a>
      </div>

      <div class="card">
        <h3>Orders</h3>
        <div style="font-size:34px;font-weight:900;"><?= (int)$orders ?></div>
        <a class="small-btn" href="<?= url('admin/orders.php') ?>">View Orders</a>
      </div>

      <div class="card">
        <h3>Users</h3>
        <div style="font-size:34px;font-weight:900;"><?= (int)$users ?></div>
        <span class="muted">From users table</span>
      </div>
    </div>

  </div>
</div>

</body>
</html>

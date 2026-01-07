<?php
require_once("../config/db.php");
require_once("../config/auth.php");
require_once("../config/helpers.php");
require_admin();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST["name"] ?? "");
  $desc = trim($_POST["description"] ?? "");
  $price = (float)($_POST["price"] ?? 0);


  if ($name === "" || $desc === "" || $price <= 0) {
    $error = "Please fill name, description, and valid price.";
  } else {
  
    $imgPath = "assets/images/placeholder.jpg"; 
    if (!empty($_FILES["image"]["name"])) {
      $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
      $allowed = ["jpg","jpeg","png","webp"];
      if (!in_array($ext, $allowed)) {
        $error = "Invalid image format. Use JPG, PNG, WEBP.";
      } else {
        $folder = "../assets/uploads/";
        if (!is_dir($folder)) mkdir($folder, 0755, true);

        $newName = "prod_" . time() . "_" . rand(1000,9999) . "." . $ext;
        $dest = $folder . $newName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $dest)) {
          $imgPath = "assets/uploads/" . $newName;
        } else {
          $error = "Image upload failed.";
        }
      }
    }

    if ($error === "") {
      $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?,?,?,?)");
      $stmt->bind_param("ssds", $name, $desc, $price, $imgPath);
      if ($stmt->execute()) {
        $success = "Product added successfully ✅";
      } else {
        $error = "Database error while inserting product.";
      }
      $stmt->close();
    }
  }
}

$list = $conn->query("SELECT id,name,price,image FROM products ORDER BY id DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Product - Admin</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="main-header">
  <div class="logo">Admin</div>
  <nav>
    <a href="<?= url('admin/index.php') ?>">Dashboard</a>
    <a href="<?= url('admin/add_product.php') ?>" class="active">Add Product</a>
    <a href="<?= url('admin/orders.php') ?>">Orders</a>
    <a href="<?= url('index.php') ?>">Back to site</a>
    <a class="btn primary" href="<?= url('auth/logout.php') ?>">Logout</a>
  </nav>
</header>

<div class="container">
  <div class="section-card" style="background:#fff;">
    <h2 style="margin:0 0 10px 0;">Add New Product</h2>

    <?php if($error): ?><div class="msg error"><?= e($error) ?></div><?php endif; ?>
    <?php if($success): ?><div class="msg success"><?= e($success) ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data" style="display:grid;gap:10px;max-width:640px;">
      <input name="name" placeholder="Product name" required>
      <textarea name="description" placeholder="Description" required
        style="padding:12px;border-radius:12px;border:1px solid #eadfe3;min-height:120px;"></textarea>
      <input name="price" type="number" step="0.01" placeholder="Price" required>
      <input name="image" type="file" accept="image/*">
      <button class="btn primary" type="submit">Add Product</button>
    </form>
  </div>

  <h2 style="margin:22px 0 10px 0;">All Products</h2>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
    <?php while($p=$list->fetch_assoc()): ?>
      <div class="card">
        <img src="<?= url(e($p["image"])) ?>" alt="">
        <h3><?= e($p["name"]) ?></h3>
        <div class="price">$<?= number_format((float)$p["price"],2) ?></div>

        <div style="display:flex;gap:10px;margin-top:12px;">
          <a class="small-btn" href="<?= url('admin/edit_product.php?id='.(int)$p["id"]) ?>">Edit</a>
          <a class="small-btn" href="<?= url('admin/delete_product.php?id='.(int)$p["id"]) ?>"
             onclick="return confirm('Delete this product?')">Delete</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

</div>

</body>
</html>

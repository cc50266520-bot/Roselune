<?php
require_once("../config/db.php");
require_once("../config/auth.php");
require_once("../config/helpers.php");
require_admin();

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) { header("Location: add_product.php"); exit(); }

$stmt = $conn->prepare("SELECT id,name,description,price,image FROM products WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) { header("Location: add_product.php"); exit(); }
$p = $res->fetch_assoc();
$stmt->close();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST["name"] ?? "");
  $desc = trim($_POST["description"] ?? "");
  $price = (float)($_POST["price"] ?? 0);

  $imgPath = $p["image"];

  if ($name === "" || $desc === "" || $price <= 0) {
    $error = "Please fill name, description, and valid price.";
  } else {
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
      $up = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
      $up->bind_param("ssdsi", $name, $desc, $price, $imgPath, $id);
      if ($up->execute()) {
        $success = "Product updated ✅";
        $p["name"]=$name; $p["description"]=$desc; $p["price"]=$price; $p["image"]=$imgPath;
      } else {
        $error = "Database error while updating.";
      }
      $up->close();
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Product - Admin</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="main-header">
  <div class="logo">Admin</div>
  <nav>
    <a href="<?= url('admin/index.php') ?>">Dashboard</a>
    <a href="<?= url('admin/add_product.php') ?>">Products</a>
    <a href="<?= url('admin/orders.php') ?>">Orders</a>
    <a href="<?= url('index.php') ?>">Back to site</a>
    <a class="btn primary" href="<?= url('auth/logout.php') ?>">Logout</a>
  </nav>
</header>

<div class="container">
  <div class="section-card" style="background:#fff;">
    <h2 style="margin:0 0 10px 0;">Edit Product</h2>

    <?php if($error): ?><div class="msg error"><?= e($error) ?></div><?php endif; ?>
    <?php if($success): ?><div class="msg success"><?= e($success) ?></div><?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:18px;align-items:start;">
      <div class="card">
        <img src="<?= url(e($p["image"])) ?>" alt="">
      </div>

      <form method="post" enctype="multipart/form-data" style="display:grid;gap:10px;">
        <input name="name" value="<?= e($p["name"]) ?>" required>
        <textarea name="description" required
          style="padding:12px;border-radius:12px;border:1px solid #eadfe3;min-height:120px;"><?= e($p["description"]) ?></textarea>
        <input name="price" type="number" step="0.01" value="<?= e($p["price"]) ?>" required>
        <input name="image" type="file" accept="image/*">
        <button class="btn primary" type="submit">Save Changes</button>
        <a class="small-btn" href="<?= url('admin/add_product.php') ?>">Back</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>

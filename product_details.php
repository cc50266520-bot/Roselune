<?php
require_once("config/db.php");
require_once("config/auth.php");
require_once("config/helpers.php");

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) { header("Location: products.php"); exit(); }

$stmt = $conn->prepare("SELECT id,name,description,price,image,category FROM products WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) { header("Location: products.php"); exit(); }
$p = $res->fetch_assoc();

$logged = isset($_SESSION["user_id"]);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= e($p["name"]) ?> - Roselune</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php navbar("products"); ?>

<div class="container">
  <div class="section-card" style="background:#fff;">
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:24px;align-items:start;">
      <div class="hero-image" style="border-radius:18px;">
        <img src="<?= e($p["image"]) ?>" alt="" style="max-height:520px;">
      </div>

      <div>
        <h2 style="margin:0;color:var(--brand);font-family:Georgia,serif;font-size:46px;"><?= e($p["name"]) ?></h2>
        <p class="muted" style="font-size:16px;"><?= e($p["description"]) ?></p>
        <p class="muted">Category: <b><?= e($p["category"]) ?></b></p>
        <div class="price" style="font-size:26px;">$<?= number_format((float)$p["price"],2) ?></div>

        <div style="margin-top:14px;display:flex;gap:12px;flex-wrap:wrap;">
          <a class="small-btn" href="products.php">Back</a>

          <?php if($logged): ?>
            <form method="post" action="add_to_cart.php" style="display:flex;gap:10px;align-items:center;">
              <input type="hidden" name="product_id" value="<?= (int)$p["id"] ?>">
              <input name="qty" type="number" value="1" min="1"
                     style="padding:12px;border-radius:12px;border:1px solid #eadfe3;width:90px;">
              <button class="btn primary" type="submit">Add to Cart</button>
            </form>
          <?php else: ?>
            <a class="btn primary" href="auth/login.php">Login to buy</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<?php $stmt->close(); ?>

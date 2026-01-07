<?php
require_once("config/db.php");
require_once("config/auth.php");
require_once("config/helpers.php");

$logged = isset($_SESSION["user_id"]);
$search = trim($_GET["q"] ?? "");

if ($search !== "") {
  $like = "%".$search."%";
  $stmt = $conn->prepare("SELECT id,name,description,price,image FROM products
     WHERE name LIKE ? OR description LIKE ?
 ORDER BY id DESC");
  $stmt->bind_param("ss", $like, $like);
} else {
  $stmt = $conn->prepare("SELECT id,name,description,price,image FROM products
       ORDER BY id DESC");
}
$stmt->execute();
$res = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Products – Rosélune</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .products-hero{
      padding: 50px 16px 10px 16px;
      text-align:center;
    }
    .products-hero h1{
      font-family: Georgia, serif;
      font-size: 52px;
      margin: 0;
      color:#b03a6b;
      letter-spacing: 2px;
    }
    .products-hero p{
      margin: 10px auto 0 auto;
      max-width: 720px;
      color:#666;
      line-height:1.7;
      font-size: 16px;
    }

    .search-box{
      max-width: 760px;
      margin: 22px auto 0 auto;
      background:#fff;
      padding: 14px;
      border-radius: 18px;
      box-shadow: 0 18px 40px rgba(0,0,0,.08);
      display:flex;
      gap:12px;
      flex-wrap:wrap;
      justify-content:center;
      align-items:center;
    }
    .search-box input{
      flex:1;
      min-width:260px;
      padding: 14px;
      border-radius: 14px;
      border: 1px solid #eadfe3;
      outline:none;
      font-size: 15px;
    }
    .search-box button{
      padding: 14px 22px;
      border:none;
      border-radius: 14px;
      background: #ff5da2;
      color:#fff;
      font-weight: 900;
      cursor:pointer;
      box-shadow: 0 14px 26px rgba(255,93,162,.35);
    }
    .search-box a{
      text-decoration:none;
      padding: 14px 18px;
      border-radius: 14px;
      background:#fff;
      border:1px solid #eadfe3;
      color:#7a2a4a;
      font-weight: 900;
    }

    .grid{
      max-width: 1200px;
      margin: 22px auto 50px auto;
      padding: 0 16px;
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 22px;
    }
    .p-card{
      background:#fff;
      border-radius: 22px;
      overflow:hidden;
      box-shadow: 0 18px 40px rgba(0,0,0,.08);
      transition: transform .2s ease;
    }
    .p-card:hover{
      transform: translateY(-4px);
    }
    .p-img{
      height: 210px;
      background:#faf6f7;
      display:flex;
      align-items:center;
      justify-content:center;
      overflow:hidden;
    }
    .p-img img{
      width:100%;
      height:100%;
      object-fit: cover;
    }
    .p-body{
      padding: 18px;
    }
    .p-body h3{
      margin: 0;
      font-size: 18px;
      color:#2b0f1d;
      font-weight: 900;
    }
    .p-body p{
      margin: 10px 0 0 0;
      color:#666;
      line-height:1.6;
      font-size: 14px;
      min-height: 44px;
    }
    .p-price{
      margin-top: 12px;
      font-size: 18px;
      font-weight: 900;
      color:#b03a6b;
    }
    .p-actions{
      display:flex;
      gap: 10px;
      margin-top: 14px;
    }
    .btn-mini{
      flex:1;
      text-align:center;
      text-decoration:none;
      padding: 12px 14px;
      border-radius: 14px;
      font-weight: 900;
      border: 1px solid #eadfe3;
      color:#7a2a4a;
      background:#fff;
    }
    .btn-buy{
      flex:1;
      padding: 12px 14px;
      border-radius: 14px;
      font-weight: 900;
      border:none;
      cursor:pointer;
      background:#ff5da2;
      color:#fff;
      box-shadow: 0 14px 26px rgba(255,93,162,.30);
    }
  </style>
</head>
<body>

<?php navbar("products"); ?>

<div class="products-hero">
  <h1>Rosélune Products</h1>
  <p>
    Explore our premium collection of foundations, blushes, and cosmetics designed to match your style.
    Use our AI feature to discover your perfect look.
  </p>

  <form class="search-box" method="get">
    <input name="q" value="<?= e($search) ?>" placeholder="Search products (foundation, blush, lipstick...)">
    <button type="submit">Search</button>
    <a href="products.php">Reset</a>
  </form>
</div>

<div class="grid">
  <?php while($p = $res->fetch_assoc()): ?>
    <div class="p-card">
      <div class="p-img">
        <img src="<?= url(e($p["image"])) ?>" alt="<?= e($p["name"]) ?>">
      </div>

      <div class="p-body">
        <h3><?= e($p["name"]) ?></h3>
        <p><?= e($p["description"]) ?></p>
        <div class="p-price">$<?= number_format((float)$p["price"], 2) ?></div>

        <div class="p-actions">
          <a class="btn-mini" href="product_details.php?id=<?= (int)$p["id"] ?>">View</a>

          <?php if($logged): ?>
            <form method="post" action="add_to_cart.php" style="flex:1;margin:0;">
              <input type="hidden" name="product_id" value="<?= (int)$p["id"] ?>">
              <input type="hidden" name="qty" value="1">
              <button class="btn-buy" type="submit">Add</button>
            </form>
          <?php else: ?>
            <a class="btn-buy" href="<?= url('auth/login.php') ?>" style="display:flex;align-items:center;justify-content:center;">
              Login to Buy
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>
<?php $stmt->close(); ?>

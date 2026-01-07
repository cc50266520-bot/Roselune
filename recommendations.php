<?php
session_start();
require_once("config/db.php");
require_once("config/helpers.php");

$logged = isset($_SESSION["user_id"]);

$skin = strtolower(trim($_GET["skin_tone"] ?? ""));
$cat  = strtolower(trim($_GET["category"] ?? ""));


if ($cat === "") $cat = "foundation";
if (!in_array($skin, ["light","medium","dark"])) $skin = ""; 

$titleLabel = ($skin !== "")
  ? "Recommended ".ucfirst($cat)." for ".ucfirst($skin)." Skin"
  : "Recommended ".ucfirst($cat)." Products";

if ($skin !== "") {
  $stmt = $conn->prepare("
    SELECT id,name,description,price,image,category,skin_tone
    FROM products
    WHERE category = ?
      AND skin_tone = ?
    ORDER BY id DESC
    LIMIT 24
  ");
  $stmt->bind_param("ss", $cat, $skin);
} else {
  $stmt = $conn->prepare("
    SELECT id,name,description,price,image,category,skin_tone
    FROM products
    WHERE category = ?
    ORDER BY id DESC
    LIMIT 24
  ");
  $stmt->bind_param("s", $cat);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($titleLabel) ?> – Rosélune</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body{background:linear-gradient(135deg,#f7d4e8,#ffe6f2);}
    .wrap{max-width:1200px;margin:26px auto 60px auto;padding:0 16px;}
    .hero{
      background:#fff;border-radius:22px;padding:22px;
      box-shadow:0 18px 40px rgba(0,0,0,.08);
      display:flex;justify-content:space-between;gap:14px;align-items:center;flex-wrap:wrap;
    }
    .hero h1{margin:0;font-family:Georgia,serif;color:#b03a6b;letter-spacing:2px;font-size:34px;}
    .hero p{margin:8px 0 0;color:#666;line-height:1.7;max-width:720px;}
    .pill{
      display:inline-block;padding:10px 14px;border-radius:999px;
      border:1px solid #eadfe3;background:#fff;color:#7a2a4a;font-weight:900;
    }
    .grid{margin-top:18px;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;}
    .cardx{background:#fff;border-radius:22px;overflow:hidden;box-shadow:0 18px 40px rgba(0,0,0,.08);}
    .img{height:210px;background:#faf6f7;}
    .img img{width:100%;height:100%;object-fit:cover;}
    .body{padding:16px;}
    .name{margin:0;font-weight:900;color:#2b0f1d;font-size:18px;}
    .desc{margin:10px 0 0;color:#666;line-height:1.6;font-size:14px;min-height:44px;}
    .meta{display:flex;justify-content:space-between;align-items:center;margin-top:12px;gap:10px;flex-wrap:wrap;}
    .tag{padding:6px 10px;border-radius:999px;border:1px solid #eadfe3;font-weight:900;color:#7a2a4a;font-size:12px;background:#fff;}
    .price{font-weight:900;color:#b03a6b;font-size:18px;}
    .actions{display:flex;gap:10px;margin-top:14px;}
    .btnmini{flex:1;text-align:center;text-decoration:none;padding:12px 14px;border-radius:14px;border:1px solid #eadfe3;font-weight:900;color:#7a2a4a;background:#fff;}
    .btnbuy{flex:1;padding:12px 14px;border-radius:14px;border:none;font-weight:900;cursor:pointer;background:#ff5da2;color:#fff;box-shadow:0 14px 26px rgba(255,93,162,.30);}
    .empty{margin-top:18px;background:#fff;border-radius:22px;padding:18px;box-shadow:0 18px 40px rgba(0,0,0,.08);color:#444;line-height:1.7;}
  </style>
</head>
<body>

<?php navbar(""); ?>

<div class="wrap">
  <div class="hero">
    <div>
      <h1><?= htmlspecialchars($titleLabel) ?></h1>
      <p>Based on your photo, Rosélune selected products that fit your skin tone. You can open details or add items to your cart.</p>
    </div>
    <?php if($skin !== ""): ?>
      <div class="pill">Detected tone: <?= htmlspecialchars(ucfirst($skin)) ?></div>
    <?php endif; ?>
  </div>

  <?php if($result->num_rows === 0): ?>
    <div class="empty">
      No products found for this skin tone in <?= htmlspecialchars(ucfirst($cat)) ?>.
      <br><br>
      ✅ Fix: Add at least 1 product with <b>category = <?= htmlspecialchars($cat) ?></b> and <b>skin_tone = <?= htmlspecialchars($skin ?: "light/medium/dark") ?></b>.
    </div>
  <?php else: ?>
    <div class="grid">
      <?php while($p=$result->fetch_assoc()): ?>
        <div class="cardx">
          <div class="img"><img src="<?= url(htmlspecialchars($p["image"])) ?>" alt=""></div>
          <div class="body">
            <h3 class="name"><?= htmlspecialchars($p["name"]) ?></h3>
            <p class="desc"><?= htmlspecialchars($p["description"]) ?></p>
            <div class="meta">
              <span class="tag"><?= htmlspecialchars($p["category"]) ?></span>
              <span class="tag">Tone: <?= htmlspecialchars($p["skin_tone"] ?? "-") ?></span>
              <span class="price">$<?= number_format((float)$p["price"],2) ?></span>
            </div>
            <div class="actions">
              <a class="btnmini" href="product_details.php?id=<?= (int)$p["id"] ?>">View</a>
              <?php if($logged): ?>
                <form method="post" action="add_to_cart.php" style="flex:1;margin:0;">
                  <input type="hidden" name="product_id" value="<?= (int)$p["id"] ?>">
                  <input type="hidden" name="qty" value="1">
                  <button class="btnbuy" type="submit">Add</button>
                </form>
              <?php else: ?>
                <a class="btnbuy" href="auth/login.php" style="display:flex;align-items:center;justify-content:center;text-decoration:none;">Login to Buy</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
<?php $stmt->close(); ?>

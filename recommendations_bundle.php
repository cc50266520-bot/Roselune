<?php
session_start();
require_once("config/db.php");
require_once("config/helpers.php");

$logged = isset($_SESSION["user_id"]);

$skin = strtolower(trim($_GET["skin_tone"] ?? ""));
if (!in_array($skin, ["light","medium","dark"])) {
  $skin = "medium";
}

function fetchProductsByCategory(mysqli $conn, string $category, string $skin, int $limit = 6) {
  $sql = "
    SELECT id,name,description,price,image,category,skin_tone
    FROM products
    WHERE category = ?
      AND (skin_tone = ? OR skin_tone = 'universal')
    ORDER BY id DESC
    LIMIT ?
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssi", $category, $skin, $limit);
  $stmt->execute();
  $res = $stmt->get_result();
  $rows = [];
  while ($row = $res->fetch_assoc()) $rows[] = $row;
  $stmt->close();
  return $rows;
}

$foundation = fetchProductsByCategory($conn, "foundation", $skin, 6);
$lipstick   = fetchProductsByCategory($conn, "lipstick",   $skin, 6);
$blush      = fetchProductsByCategory($conn, "blush",      $skin, 6);

$title = "Your Rosélune Recommendations";
$subtitle = "Detected tone: " . ucfirst($skin);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title) ?> – Rosélune</title>
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    body{background:linear-gradient(135deg,#f7d4e8,#ffe6f2);}
    .wrap{max-width:1200px;margin:26px auto 70px auto;padding:0 16px;}
    .topcard{
      background:#fff;border-radius:22px;padding:22px;
      box-shadow:0 18px 40px rgba(0,0,0,.08);
      display:flex;gap:16px;justify-content:space-between;align-items:center;flex-wrap:wrap;
    }
    .topcard h1{margin:0;font-family:Georgia,serif;color:#b03a6b;letter-spacing:1px;font-size:34px;}
    .topcard p{margin:8px 0 0;color:#666;line-height:1.7;max-width:760px;}
    .pill{
      display:inline-block;padding:10px 14px;border-radius:999px;
      border:1px solid #eadfe3;background:#fff;color:#7a2a4a;font-weight:900;
    }

    .section{
      margin-top:18px;
      background:#fff;border-radius:22px;padding:18px;
      box-shadow:0 18px 40px rgba(0,0,0,.08);
    }
    .sectionHead{
      display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;
      margin-bottom:12px;
    }
    .sectionHead h2{margin:0;color:#2b0f1d;font-size:20px;}
    .viewall{
      text-decoration:none;font-weight:900;color:#b03a6b;border:1px solid #eadfe3;
      padding:10px 12px;border-radius:14px;background:#fff;
    }

    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;}
    .cardx{background:#fff;border:1px solid #f1e6ea;border-radius:20px;overflow:hidden;}
    .img{
      height:210px;background:#fff;
      display:flex;align-items:center;justify-content:center;
      padding:14px;
    }
    .img img{width:100%;height:100%;object-fit:contain;}
    .body{padding:14px 14px 16px 14px;}
    .name{margin:0;font-weight:900;color:#2b0f1d;font-size:16px;}
    .desc{margin:8px 0 0;color:#666;line-height:1.6;font-size:13px;min-height:38px;}
    .meta{display:flex;justify-content:space-between;align-items:center;margin-top:10px;gap:10px;flex-wrap:wrap;}
    .tag{padding:6px 10px;border-radius:999px;border:1px solid #eadfe3;font-weight:900;color:#7a2a4a;font-size:12px;background:#fff;}
    .price{font-weight:900;color:#b03a6b;font-size:16px;}
    .actions{display:flex;gap:10px;margin-top:12px;}
    .btnmini{
      flex:1;text-align:center;text-decoration:none;
      padding:10px 12px;border-radius:14px;border:1px solid #eadfe3;
      font-weight:900;color:#7a2a4a;background:#fff;
    }
    .btnbuy{
      flex:1;padding:10px 12px;border-radius:14px;border:none;font-weight:900;cursor:pointer;
      background:#ff5da2;color:#fff;box-shadow:0 14px 26px rgba(255,93,162,.30);
    }
    .empty{
      padding:14px;border-radius:16px;background:#fff7fb;border:1px dashed #f0cfe0;color:#6b2a46;
      line-height:1.7;
    }
  </style>
</head>
<body>

<?php navbar(""); ?>

<div class="wrap">
  <div class="topcard">
    <div>
      <h1><?= htmlspecialchars($title) ?></h1>
      <p>
        Based on your photo, Rosélune selected products that fit your skin tone.
        You can view details or add items to your cart instantly.
      </p>
    </div>
    <div class="pill"><?= htmlspecialchars($subtitle) ?></div>
  </div>
  <div class="section">
    <div class="sectionHead">
      <h2>Foundation Picks</h2>
      <a class="viewall" href="recommendations.php?category=foundation&skin_tone=<?= urlencode($skin) ?>">View all</a>
    </div>

    <?php if (count($foundation) === 0): ?>
      <div class="empty">
        No foundation found for this tone.
        <br>✅ Add products with <b>category='foundation'</b> and <b>skin_tone='<?= htmlspecialchars($skin) ?>'</b> (or <b>universal</b>).
      </div>
    <?php else: ?>
      <div class="grid">
        <?php foreach ($foundation as $p): ?>
          <div class="cardx">
            <div class="img"><img src="<?= url($p["image"]) ?>" alt=""></div>
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
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="section">
    <div class="sectionHead">
      <h2>Lipstick Picks</h2>
      <a class="viewall" href="recommendations.php?category=lipstick&skin_tone=<?= urlencode($skin) ?>">View all</a>
    </div>

    <?php if (count($lipstick) === 0): ?>
      <div class="empty">
        No lipstick found for this tone.
        <br>✅ Add products with <b>category='lipstick'</b> and <b>skin_tone='<?= htmlspecialchars($skin) ?>'</b> (or <b>universal</b>).
      </div>
    <?php else: ?>
      <div class="grid">
        <?php foreach ($lipstick as $p): ?>
          <div class="cardx">
            <div class="img"><img src="<?= url($p["image"]) ?>" alt=""></div>
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
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="section">
    <div class="sectionHead">
      <h2>Blush Picks</h2>
      <a class="viewall" href="recommendations.php?category=blush&skin_tone=<?= urlencode($skin) ?>">View all</a>
    </div>

    <?php if (count($blush) === 0): ?>
      <div class="empty">
        No blush found for this tone.
        <br>✅ Add products with <b>category='blush'</b> and <b>skin_tone='<?= htmlspecialchars($skin) ?>'</b> (or <b>universal</b>).
      </div>
    <?php else: ?>
      <div class="grid">
        <?php foreach ($blush as $p): ?>
          <div class="cardx">
            <div class="img"><img src="<?= url($p["image"]) ?>" alt=""></div>
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
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

</body>
</html>

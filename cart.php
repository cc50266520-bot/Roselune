<?php
require_once("config/db.php");
require_once("config/auth.php");
require_once("config/helpers.php");
require_login();

$user_id = (int)$_SESSION["user_id"];


if (isset($_POST["update_qty"])) {
  $cart_id = (int)($_POST["cart_id"] ?? 0);
  $qty = (int)($_POST["qty"] ?? 1);
  if ($qty < 1) $qty = 1;

  $up = $conn->prepare("UPDATE cart SET qty=? WHERE id=? AND user_id=?");
  $up->bind_param("iii", $qty, $cart_id, $user_id);
  $up->execute();
  $up->close();
}

if (isset($_POST["remove_item"])) {
  $cart_id = (int)($_POST["cart_id"] ?? 0);
  $del = $conn->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
  $del->bind_param("ii", $cart_id, $user_id);
  $del->execute();
  $del->close();
}

$stmt = $conn->prepare("
  SELECT c.id AS cart_id, c.qty, p.id AS product_id, p.name, p.price, p.image
  FROM cart c
  JOIN products p ON p.id = c.product_id
  WHERE c.user_id=?
  ORDER BY c.added_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$items = $stmt->get_result();

$total = 0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cart - Roselune</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php navbar(""); ?>

<div class="container">
  <h2 style="margin:0 0 12px 0;">Your Cart</h2>

  <div class="section-card" style="background:#fff;">
    <?php if($items->num_rows === 0): ?>
      <p class="muted">Your cart is empty. <a href="products.php">Go shopping</a></p>
    <?php else: ?>

      <?php while($it=$items->fetch_assoc()):
        $line = (float)$it["price"] * (int)$it["qty"];
        $total += $line;
      ?>
        <div style="display:flex;gap:14px;align-items:center;padding:14px 0;border-bottom:1px solid #eee;">
          <img src="<?= e($it["image"]) ?>" style="width:110px;height:90px;object-fit:contain;border-radius:14px;background:#faf6f7;">
          <div style="flex:1;">
            <div style="font-weight:800;"><?= e($it["name"]) ?></div>
            <div class="muted">$<?= number_format((float)$it["price"],2) ?></div>
          </div>

          <form method="post" style="display:flex;gap:10px;align-items:center;">
            <input type="hidden" name="cart_id" value="<?= (int)$it["cart_id"] ?>">
            <input name="qty" type="number" value="<?= (int)$it["qty"] ?>" min="1"
                   style="padding:10px;border-radius:12px;border:1px solid #eadfe3;width:90px;">
            <button class="btn primary" name="update_qty" type="submit">Update</button>
          </form>

          <form method="post">
            <input type="hidden" name="cart_id" value="<?= (int)$it["cart_id"] ?>">
            <button class="small-btn" name="remove_item" type="submit">Remove</button>
          </form>

          <div style="width:120px;text-align:right;font-weight:900;">
            $<?= number_format($line,2) ?>
          </div>
        </div>
      <?php endwhile; ?>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:14px;">
        <div style="font-size:18px;font-weight:900;">Total: $<?= number_format($total,2) ?></div>
        <a class="btn primary" href="checkout.php">Checkout</a>
      </div>

    <?php endif; ?>
  </div>
</div>

</body>
</html>
<?php $stmt->close(); ?>

<?php
require_once("config/db.php");
require_once("config/auth.php");
require_once("config/helpers.php");
require_login();

$user_id = (int)$_SESSION["user_id"];

$stmt = $conn->prepare("
  SELECT c.qty, p.id, p.name, p.price, p.image
  FROM cart c
  JOIN products p ON p.id = c.product_id
  WHERE c.user_id=?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
$total = 0;
while($row = $res->fetch_assoc()){
  $row["subtotal"] = $row["qty"] * $row["price"];
  $total += $row["subtotal"];
  $items[] = $row;
}
$stmt->close();

if(count($items) === 0){
  header("Location: cart.php");
  exit();
}

$error = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){
  $address = trim($_POST["address"] ?? "");
  $phone = trim($_POST["phone"] ?? "");
  $payment = trim($_POST["payment_method"] ?? "cash");
  $notes = trim($_POST["notes"] ?? "");

  if($address === "" || $phone === ""){
    $error = "Please enter your address and phone number.";
  } elseif(!in_array($payment, ["cash","card"])){
    $error = "Invalid payment method.";
  } else {
  
    $_SESSION["checkout_address"] = $address;
    $_SESSION["checkout_phone"] = $phone;
    $_SESSION["checkout_payment"] = $payment;
    $_SESSION["checkout_notes"] = $notes;

    header("Location: place_order.php");
    exit();
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Checkout – Rosélune</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .checkout-grid{
      max-width:1200px;
      margin: 20px auto 50px auto;
      padding: 0 16px;
      display:grid;
      grid-template-columns: 1.1fr .9fr;
      gap: 18px;
    }
    @media(max-width:900px){ .checkout-grid{ grid-template-columns:1fr; } }

    .box{
      background:#fff;
      border-radius:22px;
      padding:20px;
      box-shadow: 0 18px 40px rgba(0,0,0,.08);
    }
    .title{
      margin:0 0 10px 0;
      color:#7a2a4a;
      font-size:22px;
      font-weight:900;
    }
    .field{
      display:grid;
      gap:6px;
      margin-top: 12px;
    }
    .field label{
      font-weight:900;
      color:#7a2a4a;
      font-size:13px;
    }
    .field input, .field textarea{
      padding:12px 14px;
      border-radius:14px;
      border:1px solid #eadfe3;
      outline:none;
      font-size:14px;
    }
    .radio-wrap{
      display:flex;
      gap:14px;
      flex-wrap:wrap;
      margin-top:10px;
    }
    .radio{
      flex:1;
      min-width:220px;
      border:1px solid #eadfe3;
      border-radius:16px;
      padding:14px;
      display:flex;
      gap:10px;
      align-items:flex-start;
      cursor:pointer;
      background:#fff;
    }
    .radio b{ color:#2b0f1d; }
    .radio small{ color:#666; display:block; margin-top:4px; line-height:1.5; }
    .line{ height:1px; background:#f1e6ea; margin:14px 0; }

    .item{
      display:flex; gap:12px; align-items:center;
      padding:10px 0;
      border-bottom:1px solid #f1e6ea;
    }
    .item img{
      width:80px; height:62px;
      border-radius:14px; object-fit:cover;
      background:#faf6f7;
    }
    .item .name{ font-weight:900; color:#2b0f1d; }
    .item .muted{ color:#666; font-size:13px; }
    .tot{
      display:flex; justify-content:space-between; align-items:center;
      font-weight:900;
      font-size:18px;
      margin-top:12px;
      color:#b03a6b;
    }
    .paybtn{
      width:100%;
      padding:14px 18px;
      border-radius:16px;
      border:none;
      background:#ff5da2;
      color:#fff;
      font-weight:900;
      cursor:pointer;
      box-shadow: 0 16px 30px rgba(255,93,162,.28);
      margin-top:14px;
      font-size:15px;
    }
    .msgerr{
      background:#ffe9ee;
      border:1px solid #ffb9c7;
      color:#7a1f35;
      padding:12px 14px;
      border-radius:14px;
      font-weight:900;
      margin-bottom:12px;
    }
  </style>
</head>
<body>

<?php navbar("checkout"); ?>

<div class="checkout-grid">

  <div class="box">
    <h2 class="title">Checkout Details</h2>
    <p class="muted" style="margin:0;color:#666;">
      Please confirm your delivery details and payment method.
    </p>

    <?php if($error): ?>
      <div class="msgerr"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="field">
        <label>Delivery Address</label>
        <input name="address" placeholder="Lebanon, Tripoli, Qalamoun – Street / Building / Floor"
               value="<?= e($_POST["address"] ?? "") ?>" required>
      </div>

      <div class="field">
        <label>Phone Number</label>
        <input name="phone" placeholder="+961 xx xxx xxx"
               value="<?= e($_POST["phone"] ?? "") ?>" required>
      </div>

      <div class="field">
        <label>Payment Method</label>

        <div class="radio-wrap">
          <label class="radio">
            <input type="radio" name="payment_method" value="cash"
              <?= (($_POST["payment_method"] ?? "cash") === "cash") ? "checked" : "" ?>>
            <div>
              <b>Cash on Delivery</b>
              <small>Pay cash when your order arrives. Simple and fast.</small>
            </div>
          </label>

          <label class="radio">
            <input type="radio" name="payment_method" value="card"
              <?= (($_POST["payment_method"] ?? "") === "card") ? "checked" : "" ?>>
            <div>
              <b>Credit / Debit Card</b>
              <small>Secure card payment (after our delivery arives pay by card)</small>
            </div>
          </label>
        </div>
      </div>

      <div class="field">
        <label>Order Notes (optional)</label>
        <textarea name="notes" rows="4" placeholder="Any delivery instructions?"><?= e($_POST["notes"] ?? "") ?></textarea>
      </div>

      <button class="paybtn" type="submit">Place Order ✅</button>
    </form>
  </div>

  
  <div class="box">
    <h2 class="title">Order Summary</h2>

    <?php foreach($items as $it): ?>
      <div class="item">
        <img src="<?= url(e($it["image"])) ?>" alt="">
        <div style="flex:1;">
          <div class="name"><?= e($it["name"]) ?></div>
          <div class="muted">Qty: <?= (int)$it["qty"] ?> • $<?= number_format((float)$it["price"],2) ?></div>
        </div>
        <div style="font-weight:900;color:#2b0f1d;">
          $<?= number_format((float)$it["subtotal"],2) ?>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="tot">
      <span>Total</span>
      <span>$<?= number_format((float)$total,2) ?></span>
    </div>

    <div class="line"></div>

    <p class="muted" style="margin:0;color:#666;line-height:1.6;">
      🔒 Your checkout is secure.  
      After placing the order, you’ll get a confirmation page.
    </p>
  </div>

</div>

</body>
</html>

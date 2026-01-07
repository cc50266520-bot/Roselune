<?php
require_once("../config/db.php");
session_start();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $u = $res->fetch_assoc();
        $uid = (int)$u["id"];

        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 15*60);

        $ins = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at, used) VALUES (?,?,?,0)");
        $ins->bind_param("iss", $uid, $token, $expires);
        $ins->execute();
        $ins->close();

        $success = "Reset link: <a href='reset_password.php?token=$token'>Reset Password</a>";
    } else {
        $error = "Email not found.";
    }

    $stmt->close();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Forgot Password - Roselune</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <h2 class="brand">Roselune</h2>
    <h3>Forgot Password</h3>

    <?php if($error): ?><div class="msg error"><?= $error ?></div><?php endif; ?>
    <?php if($success): ?><div class="msg success"><?= $success ?></div><?php endif; ?>

    <form method="post">
      <input name="email" type="email" placeholder="Enter your email" required>
      <button class="btn primary" type="submit">Send reset link</button>
    </form>

    <p class="muted"><a href="login.php">Back to login</a></p>
  </div>
</body>
</html>

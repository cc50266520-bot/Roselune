<?php
require_once("../config/db.php");
session_start();

$error = "";
$success = "";
$token = trim($_GET["token"] ?? "");
$row = null;

if ($token !== "") {
    $stmt = $conn->prepare("SELECT id, user_id FROM password_resets WHERE token=? AND used=0 AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 1) $row = $res->fetch_assoc();
    else $error = "Invalid or expired token.";
    $stmt->close();
} else {
    $error = "Token missing.";
}

if ($row && $_SERVER["REQUEST_METHOD"] === "POST") {
    $p1 = $_POST["password"] ?? "";
    $p2 = $_POST["confirm_password"] ?? "";

    if ($p1 === "" || $p2 === "") $error = "Enter password.";
    elseif ($p1 !== $p2) $error = "Passwords do not match.";
    else {
        $hash = password_hash($p1, PASSWORD_DEFAULT);
        $uid = (int)$row["user_id"];
        $rid = (int)$row["id"];

        $u = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $u->bind_param("si", $hash, $uid);
        $u->execute();
        $u->close();

        $m = $conn->prepare("UPDATE password_resets SET used=1 WHERE id=?");
        $m->bind_param("i", $rid);
        $m->execute();
        $m->close();

        $success = "Password updated! <a href='login.php'>Login now</a>";
        $row = null;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reset Password - Roselune</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <h2 class="brand">Roselune</h2>
    <h3>Reset Password</h3>

    <?php if($error): ?><div class="msg error"><?= $error ?></div><?php endif; ?>
    <?php if($success): ?><div class="msg success"><?= $success ?></div><?php endif; ?>

    <?php if($row): ?>
      <form method="post">
        <input name="password" type="password" placeholder="New password" required>
        <input name="confirm_password" type="password" placeholder="Confirm password" required>
        <button class="btn primary" type="submit">Reset</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>

<?php
require_once("../config/db.php");
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $pass  = $_POST["password"] ?? "";

    if ($email === "" || $pass === "") {
        $error = "Enter email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, is_admin FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $u = $res->fetch_assoc();

            if (password_verify($pass, $u["password"])) {
                $_SESSION["user_id"]  = (int)$u["id"];
                $_SESSION["name"]     = $u["name"];
                $_SESSION["is_admin"] = (int)$u["is_admin"];

                header("Location: ../index.php");
                exit();
            } else {
                $error = "Wrong password.";
            }
        } else {
            $error = "Email not found.";
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - Roselune</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <h2 class="brand">Roselune</h2>
    <h3>Login</h3>

    <?php if($error): ?><div class="msg error"><?= $error ?></div><?php endif; ?>

    <form method="post">
      <input name="email" type="email" placeholder="Email" required>
      <input name="password" type="password" placeholder="Password" required>
      <button class="btn primary" type="submit">Login</button>
    </form>

    <div class="auth-links">
      <a href="forgot_password.php">Forgot password?</a>
      <a href="register.php">Create account</a>
    </div>
  </div>
</body>
</html>

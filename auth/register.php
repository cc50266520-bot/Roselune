<?php
require_once("../config/db.php");
session_start();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $pass  = $_POST["password"] ?? "";
    $conf  = $_POST["confirm_password"] ?? "";

    if ($name === "" || $email === "" || $pass === "") {
        $error = "Please fill all fields.";
    } elseif ($pass !== $conf) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows;
        $stmt->close();

        if ($exists) {
            $error = "Email already exists.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $is_admin = 0;

            $stmt = $conn->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?,?,?,?)");
            $stmt->bind_param("sssi", $name, $email, $hash, $is_admin);
            if ($stmt->execute()) {
                $success = "Account created successfully! <a href='login.php'>Login now</a>";
            } else {
                $error = "Database error. Try again.";
            }
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - Roselune</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <h2 class="brand">Roselune</h2>
    <h3>Create Account</h3>

    <?php if($error): ?><div class="msg error"><?= $error ?></div><?php endif; ?>
    <?php if($success): ?><div class="msg success"><?= $success ?></div><?php endif; ?>

    <form method="post">
      <input name="name" placeholder="Full Name" required>
      <input name="email" type="email" placeholder="Email" required>
      <input name="password" type="password" placeholder="Password" required>
      <input name="confirm_password" type="password" placeholder="Confirm Password" required>
      <button class="btn primary" type="submit">Register</button>
    </form>

    <p class="muted">Already have an account? <a href="login.php">Login</a></p>
  </div>
</body>
</html>

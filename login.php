<?php
require 'db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pw = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($pw, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else $err = "Invalid credentials.";
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Login</title></head><body>
  <h2>Login</h2>
  <?php if($err) echo "<p style='color:red;'>$err</p>"; ?>
  <?php if(isset($_GET['registered'])) echo "<p style='color:green;'>Registered. Please login.</p>"; ?>
  <form method="post">
    <label>Email<br><input name="email" required></label><br>
    <label>Password<br><input name="password" type="password" required></label><br>
    <button type="submit">Login</button>
  </form>
  <p><a href="register.php">Register</a></p>
</body></html>

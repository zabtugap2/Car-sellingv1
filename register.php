<?php
require 'db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = in_array($_POST['role'] ?? 'user', ['user','host']) ? $_POST['role'] : 'user';

    if (!$name || !$email || !$password) $err = "Fill all fields.";
    else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $hash, $role]);
            header('Location: login.php?registered=1');
            exit;
        } catch (Exception $e) {
            $err = "Email might already be registered.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title></head>
<body>
  <h2>Register</h2>
  <?php if($err) echo "<p style='color:red;'>$err</p>"; ?>
  <form method="post">
    <label>Name<br><input name="name" required></label><br>
    <label>Email<br><input name="email" type="email" required></label><br>
    <label>Password<br><input name="password" type="password" required></label><br>
    <label>Role<br>
      <select name="role">
        <option value="user">User</option>
        <option value="host">Host</option>
      </select>
    </label><br>
    <button type="submit">Register</button>
  </form>
  <p><a href="login.php">Login</a></p>
</body>
</html>

<?php
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    header('Location: login.php');
    exit;
}
$msg = $_GET['msg'] ?? '';
?>
<!doctype html><html><head><meta charset="utf-8"><title>Host Dashboard</title></head><body>
<h2>Host Dashboard</h2>
<p>Welcome, <?=htmlspecialchars($_SESSION['name'])?></p>
<?php if($msg) echo "<p style='color:green;'>$msg</p>"; ?>

<h3>Upload/Replace cars CSV</h3>
<form action="upload_handler.php" method="post" enctype="multipart/form-data">
  <label>CSV file (will replace current):<br><input type="file" name="cars_csv" accept=".csv" required></label><br><br>

  <label>Upload car images (multiple allowed):<br><input type="file" name="images[]" accept="image/*" multiple></label><br>
  <small>Make sure filenames match the Image column in your CSV.</small><br><br>

  <button type="submit">Upload</button>
</form>

<p><a href="index.php">Back to home</a></p>
</body></html>

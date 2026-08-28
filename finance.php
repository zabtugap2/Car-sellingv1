<?php
require 'db.php';

$price = isset($_GET['price']) ? floatval($_GET['price']) : 0;
$monthly = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $price = floatval($_POST['price']);
    $down = floatval($_POST['down']);
    $interest = floatval($_POST['interest'])/100; // annual percent to decimal
    $years = intval($_POST['years']);

    if ($down < 0 || $years <= 0) $errors[] = "Invalid inputs.";
    else {
        $loan = max(0, $price - $down);
        $r = $interest / 12; // monthly rate
        $n = $years * 12;
        if ($r == 0) {
            $monthly = ($n>0) ? ($loan / $n) : 0;
        } else {
            $monthly = $loan * ($r) / (1 - pow(1+$r, -$n));
        }
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Financing</title></head><body>
<h2>Financing Estimator</h2>
<?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<form method="post">
  <label>Car Price<br><input name="price" value="<?=htmlspecialchars($price)?>" required></label><br>
  <label>Down Payment<br><input name="down" value="0" required></label><br>
  <label>Annual Interest (%)<br><input name="interest" value="7.0" required></label><br>
  <label>Term (years)<br><input name="years" value="3" required></label><br>
  <button type="submit">Calculate</button>
</form>

<?php if($monthly !== null): ?>
  <h3>Result</h3>
  <p>Monthly payment: ₱<?=number_format($monthly,2)?></p>
  <p>Total paid (monthly * months): ₱<?=number_format($monthly * ($years*12),2)?></p>
<?php endif; ?>

<p><a href="index.php">Back to listings</a></p>
</body></html>

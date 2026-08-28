<?php
session_start();

$csvFile = "C:/Users/zabtu/OneDrive/car-selling/uploads/clean.csv";

$cars = [];

echo "<p>Looking for file: $csvFile</p>";

if (file_exists($csvFile)) {
    echo "<p>✅ File found.</p>";

    if (($h = fopen($csvFile, 'r')) !== false) {
        echo "<p>✅ File opened.</p>";

        $headers = fgetcsv($h);
        echo "<pre>Headers:\n";
        print_r($headers);
        echo "</pre>";

        // Clean headers
        $headers = array_map(function($h) {
            return trim(preg_replace('/\x{FEFF}/u', '', $h));
        }, $headers);

        echo "<pre>Cleaned Headers:\n";
        print_r($headers);
        echo "</pre>";

        while (($row = fgetcsv($h)) !== false) {
            echo "<pre>Row:\n";
            print_r($row);
            echo "</pre>";

            if (count($row) === count($headers)) {
                $cars[] = array_combine($headers, $row);
            }
        }
        fclose($h);
    } else {
        echo "<p>❌ Could not open file.</p>";
    }
} else {
    echo "<p>❌ File not found at $csvFile</p>";
}

echo "<pre>Final Cars Array:\n";
print_r($cars);
echo "</pre>";
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Car Listings</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header>
    <h1>Car Selling</h1>
    <nav>
      <?php if(isset($_SESSION['user_id'])): ?>
        Hello <?=htmlspecialchars($_SESSION['name'])?> |
        <a href="chat.php">Messages</a> |
        <?php if($_SESSION['role'] === 'host'): ?><a href="dashboard.php">Host Dashboard</a> |<?php endif; ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a> | <a href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </header>

  <main>
    <?php if(empty($cars)): ?>
      <p>No cars available. Host should upload a CSV at dashboard.</p>
    <?php else: ?>
      <div class="cards">
      <?php foreach($cars as $c): ?>
        <div class="card">
          <?php $img = 'uploads/images/' . ($c['Image'] ?? ''); ?>
          <img src="<?=file_exists($img) ? $img : 'assets/images/placeholder.png'?>" alt="" style="max-width:200px;">
          <h3><?=htmlspecialchars($c['Model'])?></h3>
          <p><?=htmlspecialchars($c['Type'])?> — <?=htmlspecialchars($c['Color'])?></p>
          <p>Price: ₱<?=number_format((float)$c['Price'])?></p>
          <p><?=htmlspecialchars($c['Description'] ?? '')?></p>
          <p>
            <a href="finance.php?price=<?=urlencode($c['Price'])?>">Financing Estimator</a>
            <?php if(isset($_SESSION['user_id'])): ?>
              | <a href="chat.php?to_host=1&car=<?=urlencode($c['Model'])?>">Message Host</a>
            <?php else: ?>
              | <a href="login.php">Login to message</a>
            <?php endif; ?>
          </p>
        </div>
      <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>

<?php
// db.php
session_start();

$DB_HOST = 'localhost';
$DB_NAME = 'car_selling';
$DB_USER = 'root';
$DB_PASS = ''; // XAMPP default

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    die('DB Connection error: ' . $e->getMessage());
}
?>

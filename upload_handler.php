<?php
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'host') {
    header('Location: login.php');
    exit;
}

$uploadDir = __DIR__ . '/uploads/';
$imgDir = $uploadDir . 'images/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSV
    if (isset($_FILES['cars_csv']) && $_FILES['cars_csv']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['cars_csv']['tmp_name'];
        $dest = $uploadDir . 'cars.csv';
        if (move_uploaded_file($tmp, $dest)) {
            $msg = "CSV uploaded.";
        } else {
            $msg = "CSV upload failed.";
        }
    } else {
        $msg = "CSV not provided or upload error.";
    }

    // Images
    if (!is_dir($imgDir)) mkdir($imgDir, 0755, true);
    if (!empty($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $name = basename($_FILES['images']['name'][$i]);
                // basic extension check
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','gif'])) {
                    move_uploaded_file($tmp, $imgDir . $name);
                }
            }
        }
    }

    header('Location: dashboard.php?msg=' . urlencode($msg));
    exit;
}

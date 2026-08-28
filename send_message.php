<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) { http_response_code(403); exit; }
$sender = $_SESSION['user_id'];
$to = intval($_POST['to'] ?? 0);
$msg = trim($_POST['message'] ?? '');
if ($to && $msg) {
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$sender, $to, $msg]);
}

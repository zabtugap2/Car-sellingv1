<?php
require 'db.php';
$a = intval($_GET['a'] ?? 0);
$b = intval($_GET['b'] ?? 0);
if (!$a || !$b) { echo json_encode([]); exit; }
$stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC");
$stmt->execute([$a,$b,$b,$a]);
$data = $stmt->fetchAll();
header('Content-Type: application/json');
echo json_encode($data);

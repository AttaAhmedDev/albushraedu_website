<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false]);
    exit;
}

// هات مسار الملف
$stmt = $pdo->prepare("SELECT file_path FROM letters WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if ($file && file_exists($file['file_path'])) {
    unlink($file['file_path']); // حذف من السيرفر
}

// حذف من الداتابيز
$stmt = $pdo->prepare("DELETE FROM letters WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(['success' => true]);

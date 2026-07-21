<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;

if (!$id) {
    echo "Invalid ID";
    exit;
}

// هات الملف عشان تمسحه من السيرفر
$stmt = $pdo->prepare("SELECT file_path FROM english_worksheet WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if ($file) {
    if (file_exists($file['file_path'])) {
        unlink($file['file_path']); // حذف من السيرفر
    }

    $stmt = $pdo->prepare("DELETE FROM english_worksheet WHERE id = ?");
    $stmt->execute([$id]);

    echo "File deleted successfully";
} else {
    echo "File not found";
}

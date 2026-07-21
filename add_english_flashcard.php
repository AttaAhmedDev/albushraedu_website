<?php
require_once 'db.php';

header('Content-Type: application/json');

$letter = $_POST['letter'] ?? '';
$title = $_POST['title'] ?? '';
$file  = $_FILES['file'] ?? null;

if (!$title || !$file || !$letter) {
    echo json_encode(['success' => false]);
    exit;
}

// فولدر الرفع
$uploadDir = "uploads/files/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// اسم ملف عشوائي
$fileName = time() . '_' . basename($file['name']);
$targetPath = $uploadDir . $fileName;

// رفع الملف
if (move_uploaded_file($file['tmp_name'], $targetPath)) {

    $stmt = $pdo->prepare("INSERT INTO letters (file_name, file_path, letter) VALUES (?, ?, ?)");
    $stmt->execute([$title, $targetPath, $letter]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

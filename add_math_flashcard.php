<?php
require_once 'db.php';

header('Content-Type: application/json');

$number = $_POST['number'] ?? '';
$title = $_POST['title'] ?? '';
$file  = $_FILES['file'] ?? null;

if (!$title || !$file || !$number) {
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

    $stmt = $pdo->prepare("INSERT INTO numbers (file_name, file_path, number_file) VALUES (?, ?, ?)");
    $stmt->execute([$title, $targetPath, $number]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

<?php
require_once 'db.php';

header('Content-Type: application/json');

$title = $_POST['title'] ?? '';
$file  = $_FILES['file'] ?? null;

if (!$title || !$file) {
    echo json_encode(["success" => false, "error" => "Missing data"]);
    exit;
}

$uploadDir = "uploads/files/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// تجهيز اسم الملف
$originalName = basename($file['name']);
$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

// أنواع مسموحة
$allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];

if (!in_array($extension, $allowed)) {
    echo json_encode(["success" => false, "error" => "File type not allowed"]);
    exit;
}

// اسم نظيف
$fileName = time() . "_" . uniqid() . "." . $extension;

$targetPath = $uploadDir . $fileName;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {

    $stmt = $pdo->prepare("INSERT INTO sight_worksheet (title, file_path) VALUES (?, ?)");
    $stmt->execute([$title, $targetPath]);

    echo json_encode([
        "success" => true,
        "message" => "File uploaded successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => "Upload failed"
    ]);
}

exit;

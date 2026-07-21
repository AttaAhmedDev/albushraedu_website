<?php
require_once 'db.php';

$title = $_POST['title'];
$file = $_FILES['file'];

$uploadDir = "uploads/files/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// اسم الملف الجديد (بدون مسافات أو مشاكل)
$originalName = basename($file['name']);
$extension = pathinfo($originalName, PATHINFO_EXTENSION);
$fileName = time() . "_" . uniqid() . "." . $extension;

$targetPath = $uploadDir . $fileName;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {

    $stmt = $pdo->prepare("INSERT INTO english_worksheet (title, file_path) VALUES (?, ?)");
    $stmt->execute([$title, $targetPath]);

    echo "File uploaded successfully";
} else {
    echo "Upload failed";
}

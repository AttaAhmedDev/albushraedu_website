<?php
require_once 'db.php';

header('Content-Type: application/json');

$title    = $_POST['title'] ?? '';
$category = $_POST['category'] ?? '';
$file     = $_FILES['file'] ?? null;

if (!$title || !$file || !$category) {
    echo json_encode(["success" => false, "error" => "Missing data"]);
    exit;
}

// 🧠 تحديد الجدول حسب الكاتيجوري
switch ($category) {
    case 'word_families':
        $table = 'word_presentation';
        break;
    case 'sight_words':
        $table = 'sight_presentation';
        break;
    case 'english':
        $table = 'english_presentation';
        break;
    case 'math':
        $table = 'math_presentation';
        break;
    default:
        echo json_encode(["success" => false, "error" => "Invalid category"]);
        exit;
}

// مسار الرفع
$uploadDir = "uploads/files/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// تجهيز الملف
$originalName = basename($file['name']);
$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

// السماح بـ PPT فقط
$allowed = ['ppt', 'pptx'];

if (!in_array($extension, $allowed)) {
    echo json_encode(["success" => false, "error" => "Only PPT/PPTX allowed"]);
    exit;
}

// اسم آمن
$fileName = time() . "_" . uniqid() . "." . $extension;
$targetPath = $uploadDir . $fileName;

// رفع الملف
if (move_uploaded_file($file['tmp_name'], $targetPath)) {

    $stmt = $pdo->prepare("INSERT INTO $table (title, file_path) VALUES (?, ?)");
    $stmt->execute([$title, $targetPath]);

    echo json_encode([
        "success" => true,
        "message" => "Presentation uploaded successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => "Upload failed"
    ]);
}

exit;

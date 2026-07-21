<?php
require_once 'db.php';

header('Content-Type: application/json');

// استلام JSON
$data = json_decode(file_get_contents("php://input"), true);

$category = $data['category'] ?? '';
$id       = $data['id'] ?? null;

if (!$category || !$id) {
    echo json_encode(["success" => false, "error" => "Missing data"]);
    exit;
}

// 🧠 تحديد الجدول
$map = [
    'word_families' => 'word_presentation',
    'sight_words'   => 'sight_presentation',
    'english'       => 'english_presentation',
    'math'          => 'math_presentation'
];

if (!isset($map[$category])) {
    echo json_encode(["success" => false, "error" => "Invalid category"]);
    exit;
}

$table = $map[$category];

// 🗑️ هات مسار الملف الأول عشان تمسحه من السيرفر
$stmt = $pdo->prepare("SELECT file_path FROM $table WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if ($file) {
    $filePath = $file['file_path'];

    // حذف من السيرفر
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // حذف من الداتابيز
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "File not found"]);
}

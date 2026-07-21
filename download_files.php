<?php
require_once 'db.php';

$id = $_GET['id'] ?? null;
$table = $_GET['table'] ?? null;

if (!$id || !$table) {
    die("Invalid request");
}

// حماية - اسمح بجداول معينة فقط
$allowedTables = ['word_worksheet', 'math_worksheet', 'english_worksheet', 'sight_worksheet', 'word_presentation', 'math_presentation', 'english_presentation', 'sight_presentation'];

if (!in_array($table, $allowedTables)) {
    die("Invalid table");
}

// استعلام ديناميك
$stmt = $pdo->prepare("SELECT file_path, title FROM $table WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) {
    die("File not found");
}

$path = $file['file_path'];
$title = $file['title'];

if (!file_exists($path)) {
    die("File missing on server");
}

// تنظيف الاسم
$safeName = preg_replace('/[^a-zA-Z0-9-_ ]/', '', $title);

// الامتداد
$ext = pathinfo($path, PATHINFO_EXTENSION);

// تحميل
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $safeName . '.' . $ext . '"');
header('Content-Length: ' . filesize($path));

readfile($path);
exit;

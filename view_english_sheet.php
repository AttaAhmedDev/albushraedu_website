<?php
require_once 'db.php';
if (!isset($_GET['id'])) {
    die('Invalid request');
}
$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT file_path, title FROM english_worksheet WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    die('File not found');
}
$filePath = $file['file_path'];
// لو المسار نسبي في الداتابيز
$fullPath = __DIR__ . '/' . $filePath;
// تأكد إن الملف موجود
if (!file_exists($fullPath)) {
    die('File does not exist');
}
// تنظيف الـ buffer
if (ob_get_length()) ob_end_clean();
// إعداد الهيدر
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($fullPath) . '"');
header('Content-Length: ' . filesize($fullPath));
header('Accept-Ranges: bytes');
// عرض الملف
readfile($fullPath);
exit;

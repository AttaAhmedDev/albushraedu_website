<?php
require_once 'db.php';

if (!isset($_GET['letter'])) {
    die('Invalid request');
}

$letter = strtoupper($_GET['letter']);

$stmt = $pdo->prepare("SELECT * FROM letters WHERE letter = ?");
$stmt->execute([$letter]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    die('File not found');
}

$filePath = $file['file_path'];
$fullPath = __DIR__ . '/' . $filePath;

if (!file_exists($fullPath)) {
    die('File does not exist');
}

// اسم الملف من الداتابيز
$filename = $file['file_name'] . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($fullPath));

readfile($fullPath);
exit;

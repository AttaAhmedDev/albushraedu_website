<?php
require_once 'db.php';

if (!isset($_GET['number'])) {
    die('Invalid request');
}

$number = intval($_GET['number']);

$stmt = $pdo->prepare("SELECT * FROM numbers WHERE number_file = ?");
$stmt->execute([$number]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    die('File not found');
}

$fullPath = __DIR__ . '/' . $file['file_path'];

if (!file_exists($fullPath)) {
    die('File does not exist');
}
$filename = $file['file_name'] . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($fullPath));

readfile($fullPath);
exit;

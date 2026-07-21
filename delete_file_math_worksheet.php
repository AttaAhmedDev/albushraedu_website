<?php
require_once 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "error" => "Invalid ID"]);
    exit;
}

// هات الملف عشان تمسحه من السيرفر
$stmt = $pdo->prepare("SELECT file_path FROM math_worksheet WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if ($file) {

    if (file_exists($file['file_path'])) {
        unlink($file['file_path']);
    }

    $stmt = $pdo->prepare("DELETE FROM math_worksheet WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode([
        "success" => true,
        "message" => "File deleted successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => "File not found"
    ]);
}

exit;

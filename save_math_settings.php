<?php
session_start();
require_once 'check_admin.php';
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$mName = trim($input['mName'] ?? '');
$mLink = trim($input['mLink'] ?? '');

if (empty($mName) || empty($mLink)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $sql = "INSERT INTO math_settings (mName, mLink)
            VALUES (:mName, :mLink)
            ON DUPLICATE KEY UPDATE mLink = VALUES(mLink)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':mName' => $mName,
        ':mLink' => $mLink
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB Error']);
}

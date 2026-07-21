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

$gName = trim($input['gName'] ?? '');
$gLink = trim($input['gLink'] ?? '');

if (empty($gName) || empty($gLink)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $sql = "INSERT INTO game_settings (gName, gLink)
            VALUES (:gName, :gLink)
            ON DUPLICATE KEY UPDATE gLink = VALUES(gLink)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':gName' => $gName,
        ':gLink' => $gLink
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB Error']);
}

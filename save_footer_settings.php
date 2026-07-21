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

$instagram = trim($input['instagram'] ?? '');
$email     = trim($input['email'] ?? '');
$phone     = trim($input['phone'] ?? '');

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'صيغة البريد الإلكتروني غير صحيحة']);
    exit;
}

try {
    $sql = "INSERT INTO site_settings (setting_key, setting_value)
            VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";

    $stmt = $pdo->prepare($sql);

    $pdo->beginTransaction();

    $stmt->execute([':key' => 'footer_instagram', ':value' => $instagram]);
    $stmt->execute([':key' => 'footer_email', ':value' => $email]);
    $stmt->execute([':key' => 'footer_phone', ':value' => $phone]);

    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'DB Error']);
}

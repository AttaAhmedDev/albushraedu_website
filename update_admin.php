<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

// check admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

// get JSON body
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// check data
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit();
}

$newEmail = trim($data['newEmail'] ?? '');
$currentPassword = trim($data['currentPassword'] ?? '');
$newPassword = trim($data['newPassword'] ?? '');

if (!$newEmail || !$currentPassword || !$newPassword) {
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
    exit();
}

// ✅ استخدم ID بدل email
$stmt = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($currentPassword, $admin['password'])) {
    echo json_encode(['success' => false, 'error' => 'Wrong password']);
    exit();
}

// update admin
$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

// ✅ update باستخدام ID
$stmt = $pdo->prepare("UPDATE admins SET email = ?, password = ? WHERE id = ?");
$ok = $stmt->execute([$newEmail, $hashed, $_SESSION['admin_id']]);

if ($ok) {
    $_SESSION['email'] = $newEmail; // تحديث السيشن
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'DB update failed']);
}

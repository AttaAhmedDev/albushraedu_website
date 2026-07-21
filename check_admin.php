<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    die(json_encode(['error' => 'غير مصرح بالوصول']));
}

// التحقق من حالة تسجيل الدخول
$isLoggedIn = isset($_SESSION['role']);
$userName = '';
$userRole = '';

if ($isLoggedIn) {
    $userName = $_SESSION['student_name'] ?? $_SESSION['admin_name'] ?? 'User';
    $userRole = $_SESSION['role'] ?? '';
}

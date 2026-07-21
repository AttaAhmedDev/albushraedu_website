<?php

// ⚙️ إعدادات قاعدة البيانات
define('DB_HOST',     'localhost');   // سيرفر قاعدة البيانات
define('DB_USER',     'root');        // اسم المستخدم
define('DB_PASS',     '');            // كلمة المرور
define('DB_NAME',     'kids_app');    // اسم قاعدة البيانات
define('DB_CHARSET',  'utf8mb4');

// 🔌 الاتصال بقاعدة البيانات باستخدام PDO
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // إظهار الأخطاء
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // نتائج على شكل array
        PDO::ATTR_EMULATE_PREPARES   => false,                    // Prepared Statements حقيقية
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // ❌ فشل الاتصال
    die(json_encode([
        'status'  => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]));
}

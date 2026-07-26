<?php
// Thin wrapper so /api works even if rewrite is awkward on some hosts.
// Prefer root .htaccess rewrite to backend/public/index.php.
$_GET['route'] = $_GET['route'] ?? '';
if ($_GET['route'] === '' && isset($_SERVER['PATH_INFO'])) {
    $_GET['route'] = ltrim($_SERVER['PATH_INFO'], '/');
}
require dirname(__DIR__) . '/backend/public/index.php';

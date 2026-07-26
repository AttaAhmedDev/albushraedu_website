<?php
// Fallback entry when DirectoryIndex is used (no rewrite needed for home page)
$index = __DIR__ . '/frontend/dist/index.html';
if (!is_file($index)) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Frontend build missing. Run: cd frontend && npm run build";
    exit;
}
header('Content-Type: text/html; charset=utf-8');
readfile($index);

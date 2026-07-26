<?php

require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Response.php';
require_once __DIR__ . '/../FileHelper.php';
require_once __DIR__ . '/../Middleware/RequireAdmin.php';

class WorksheetController
{
    private const TABLES = [
        'english' => 'english_worksheet',
        'math'    => 'math_worksheet',
        'sight'   => 'sight_worksheet',
        'word'    => 'word_worksheet',
    ];

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function table(string $type): string
    {
        if (!isset(self::TABLES[$type])) {
            Response::error('Invalid worksheet type', 400);
        }
        return self::TABLES[$type];
    }

    public function list(array $params): void
    {
        $table = $this->table($params['type']);
        $stmt = $this->pdo->prepare("SELECT id, title, file_path FROM `$table` ORDER BY id DESC");
        $stmt->execute();
        Response::success(['items' => $stmt->fetchAll()]);
    }

    public function upload(array $params): void
    {
        RequireAdmin::handle();
        $table = $this->table($params['type']);
        $title = trim($_POST['title'] ?? '');
        $file = $_FILES['file'] ?? null;

        if ($title === '' || !$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            Response::error('Title and file are required');
        }

        try {
            $path = FileHelper::storeUpload($file);
            $stmt = $this->pdo->prepare("INSERT INTO `$table` (title, file_path) VALUES (?, ?)");
            $stmt->execute([$title, $path]);
            Response::success(['id' => (int)$this->pdo->lastInsertId(), 'message' => 'File uploaded successfully'], 201);
        } catch (RuntimeException $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function delete(array $params): void
    {
        RequireAdmin::handle();
        $table = $this->table($params['type']);
        $id = (int)($params['id'] ?? 0);
        if ($id < 1) {
            Response::error('Invalid ID');
        }

        $stmt = $this->pdo->prepare("SELECT file_path FROM `$table` WHERE id = ?");
        $stmt->execute([$id]);
        $file = $stmt->fetch();
        if (!$file) {
            Response::error('File not found', 404);
        }

        FileHelper::deleteIfExists($file['file_path']);
        $del = $this->pdo->prepare("DELETE FROM `$table` WHERE id = ?");
        $del->execute([$id]);
        Response::success(['message' => 'File deleted successfully']);
    }

    public function view(array $params): void
    {
        $this->serve($params, true);
    }

    public function download(array $params): void
    {
        $this->serve($params, false);
    }

    private function serve(array $params, bool $inline): void
    {
        $table = $this->table($params['type']);
        $id = (int)($params['id'] ?? 0);
        $stmt = $this->pdo->prepare("SELECT file_path, title FROM `$table` WHERE id = ?");
        $stmt->execute([$id]);
        $file = $stmt->fetch();
        if (!$file) {
            Response::error('File not found', 404);
        }

        $ext = pathinfo($file['file_path'], PATHINFO_EXTENSION);
        $safeName = preg_replace('/[^a-zA-Z0-9-_ ]/', '', $file['title']) . ($ext ? '.' . $ext : '');
        $mime = strtolower($ext) === 'pdf' ? 'application/pdf' : 'application/octet-stream';
        FileHelper::stream($file['file_path'], $safeName, $inline, $mime);
    }
}

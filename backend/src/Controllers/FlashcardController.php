<?php

require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Response.php';
require_once __DIR__ . '/../FileHelper.php';
require_once __DIR__ . '/../Middleware/RequireAdmin.php';

class FlashcardController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function list(array $params): void
    {
        $kind = $params['kind'] ?? '';
        if ($kind === 'letters') {
            $stmt = $this->pdo->query('SELECT id, file_name, file_path, letter FROM letters ORDER BY id DESC');
            Response::success(['items' => $stmt->fetchAll()]);
        }
        if ($kind === 'numbers') {
            $stmt = $this->pdo->query('SELECT id, file_name, file_path, number_file FROM numbers ORDER BY id DESC');
            Response::success(['items' => $stmt->fetchAll()]);
        }
        Response::error('Invalid flashcard kind', 400);
    }

    public function upload(array $params): void
    {
        RequireAdmin::handle();
        $kind = $params['kind'] ?? '';
        $title = trim($_POST['title'] ?? '');
        $file = $_FILES['file'] ?? null;

        if ($title === '' || !$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            Response::error('Title and file are required');
        }

        try {
            $path = FileHelper::storeUpload($file);

            if ($kind === 'letters') {
                $letter = strtoupper(trim($_POST['letter'] ?? ''));
                if ($letter === '') {
                    Response::error('Letter is required');
                }
                $stmt = $this->pdo->prepare('INSERT INTO letters (file_name, file_path, letter) VALUES (?, ?, ?)');
                $stmt->execute([$title, $path, $letter]);
                Response::success(['id' => (int)$this->pdo->lastInsertId()], 201);
            }

            if ($kind === 'numbers') {
                $number = trim($_POST['number'] ?? '');
                if ($number === '') {
                    Response::error('Number is required');
                }
                $stmt = $this->pdo->prepare('INSERT INTO numbers (file_name, file_path, number_file) VALUES (?, ?, ?)');
                $stmt->execute([$title, $path, $number]);
                Response::success(['id' => (int)$this->pdo->lastInsertId()], 201);
            }

            Response::error('Invalid flashcard kind', 400);
        } catch (RuntimeException $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function delete(array $params): void
    {
        RequireAdmin::handle();
        $kind = $params['kind'] ?? '';
        $id = (int)($params['id'] ?? 0);
        if ($id < 1) {
            Response::error('Invalid ID');
        }

        if ($kind === 'letters') {
            $stmt = $this->pdo->prepare('SELECT file_path FROM letters WHERE id = ?');
            $stmt->execute([$id]);
            $file = $stmt->fetch();
            if (!$file) {
                Response::error('Not found', 404);
            }
            FileHelper::deleteIfExists($file['file_path']);
            $this->pdo->prepare('DELETE FROM letters WHERE id = ?')->execute([$id]);
            Response::success(['message' => 'Deleted']);
        }

        if ($kind === 'numbers') {
            $stmt = $this->pdo->prepare('SELECT file_path FROM numbers WHERE id = ?');
            $stmt->execute([$id]);
            $file = $stmt->fetch();
            if (!$file) {
                Response::error('Not found', 404);
            }
            FileHelper::deleteIfExists($file['file_path']);
            $this->pdo->prepare('DELETE FROM numbers WHERE id = ?')->execute([$id]);
            Response::success(['message' => 'Deleted']);
        }

        Response::error('Invalid flashcard kind', 400);
    }

    public function download(array $params): void
    {
        $kind = $params['kind'] ?? '';
        $key = $params['key'] ?? '';

        if ($kind === 'letters') {
            $letter = strtoupper($key);
            $stmt = $this->pdo->prepare('SELECT * FROM letters WHERE letter = ?');
            $stmt->execute([$letter]);
            $file = $stmt->fetch();
            if (!$file) {
                Response::error('File not found', 404);
            }
            $filename = $file['file_name'] . '.pdf';
            FileHelper::stream($file['file_path'], $filename, false, 'application/pdf');
        }

        if ($kind === 'numbers') {
            $number = (int)$key;
            $stmt = $this->pdo->prepare('SELECT * FROM numbers WHERE number_file = ?');
            $stmt->execute([$number]);
            $file = $stmt->fetch();
            if (!$file) {
                Response::error('File not found', 404);
            }
            $filename = $file['file_name'] . '.pdf';
            FileHelper::stream($file['file_path'], $filename, false, 'application/pdf');
        }

        Response::error('Invalid flashcard kind', 400);
    }
}

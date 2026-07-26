<?php

require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Response.php';
require_once __DIR__ . '/../Middleware/RequireAdmin.php';

class AdminController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function updateProfile(): void
    {
        RequireAdmin::handle();
        $data = Auth::jsonBody();
        $newEmail = trim($data['newEmail'] ?? $data['email'] ?? '');
        $currentPassword = $data['currentPassword'] ?? '';
        $newPassword = $data['newPassword'] ?? '';

        if ($newEmail === '' || $currentPassword === '' || $newPassword === '') {
            Response::error('Missing fields');
        }

        $user = Auth::user();
        $stmt = $this->pdo->prepare('SELECT password FROM admins WHERE id = ?');
        $stmt->execute([$user['id']]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($currentPassword, $admin['password'])) {
            Response::error('Wrong password', 401);
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $ok = $this->pdo->prepare('UPDATE admins SET email = ?, password = ? WHERE id = ?')
            ->execute([$newEmail, $hashed, $user['id']]);

        if (!$ok) {
            Response::error('DB update failed', 500);
        }

        Auth::startSession();
        $_SESSION['email'] = $newEmail;
        Response::success(['message' => 'Profile updated', 'user' => Auth::user()]);
    }
}

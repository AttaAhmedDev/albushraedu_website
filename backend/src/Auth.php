<?php

class Auth
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/OnlineLearningPlatform/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function user(): ?array
    {
        self::startSession();

        if (!isset($_SESSION['role'])) {
            return null;
        }

        $role = $_SESSION['role'];

        if ($role === 'admin') {
            return [
                'role'  => 'admin',
                'id'    => $_SESSION['admin_id'] ?? null,
                'name'  => $_SESSION['admin_name'] ?? 'Admin',
                'email' => $_SESSION['email'] ?? null,
            ];
        }

        if ($role === 'student') {
            return [
                'role' => 'student',
                'id'   => $_SESSION['student_id'] ?? null,
                'name' => $_SESSION['student_name'] ?? 'Student',
            ];
        }

        return null;
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'admin';
    }

    public static function loginAdmin(array $admin): void
    {
        self::startSession();
        $_SESSION['role'] = 'admin';
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['email'] = $admin['email'];
        $_SESSION['admin_logged_in'] = true;
    }

    public static function loginStudent(array $student): void
    {
        self::startSession();
        $_SESSION['role'] = 'student';
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['name'];
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    public static function jsonBody(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}

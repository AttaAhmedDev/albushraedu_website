<?php

require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Response.php';

class AuthController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function me(): void
    {
        $user = Auth::user();
        if (!$user) {
            Response::error('Not authenticated', 401);
        }
        Response::success(['user' => $user]);
    }

    public function login(): void
    {
        $data = Auth::jsonBody();
        $role = $data['role'] ?? '';
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if ($email === '') {
            Response::error('Please enter your email.');
        }

        if ($role === 'admin') {
            if ($password === '') {
                Response::error('Please enter your password.');
            }
            $stmt = $this->pdo->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $admin = $stmt->fetch();

            if (!$admin || !password_verify($password, $admin['password'])) {
                Response::error('Wrong admin email or password.', 401);
            }

            Auth::loginAdmin($admin);
            Response::success(['user' => Auth::user()]);
        }

        if ($role === 'student') {
            $stmt = $this->pdo->prepare('SELECT * FROM students WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $student = $stmt->fetch();

            if (!$student) {
                Response::error('No student found with this email.', 401);
            }
            if (empty($student['password'])) {
                Response::error('Account setup incomplete. Please contact admin.');
            }
            if ($password === '') {
                Response::error('Please enter your password.');
            }
            if (!password_verify($password, $student['password'])) {
                Response::error('Wrong password.', 401);
            }

            Auth::loginStudent($student);
            Response::success(['user' => Auth::user()]);
        }

        Response::error('Invalid role selected.');
    }

    public function register(): void
    {
        $data = Auth::jsonBody();
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $age = trim((string)($data['age'] ?? ''));

        if ($name === '' || $email === '' || $password === '') {
            Response::error('Please fill all required fields (Name, Email, Password).');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::error('Please enter a valid email address.');
        }
        if (strlen($password) < 4) {
            Response::error('Password must be at least 4 characters long.');
        }

        $check = $this->pdo->prepare('SELECT id FROM students WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            Response::error('This email is already registered. Please login instead.');
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $this->pdo->prepare('INSERT INTO students (name, email, password, age) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $email, $hashed, $age]);
            $id = (int)$this->pdo->lastInsertId();
            Auth::loginStudent(['id' => $id, 'name' => $name]);
            Response::success(['user' => Auth::user()], 201);
        } catch (PDOException $e) {
            if ((int)$e->getCode() === 23000) {
                Response::error('This email is already registered.');
            }
            Response::error('Registration failed. Please try again.', 500);
        }
    }

    public function logout(): void
    {
        Auth::logout();
        Response::success(['message' => 'Logged out']);
    }
}

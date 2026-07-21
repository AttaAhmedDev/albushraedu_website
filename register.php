<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $age      = trim($_POST['age'] ?? '');

    // التحقق من صحة المدخلات
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill all required fields (Name, Email, Password).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 4) {
        $error = 'Password must be at least 4 characters long.';
    } else {

        // التحقق من عدم وجود البريد الإلكتروني مسبقاً
        $check = $pdo->prepare("SELECT id FROM students WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = 'This email is already registered. Please login instead.';
        } else {

            // تشفير كلمة المرور
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                // إدراج الطالب الجديد (مرة واحدة فقط)
                $stmt = $pdo->prepare("INSERT INTO students (name, email, password, age) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password, $age]);

                // تسجيل دخول تلقائي
                $_SESSION['success_message'] = "🎉 Welcome $name! Your account has been created successfully.";
                $_SESSION['role']         = 'student';
                $_SESSION['student_id']   = $pdo->lastInsertId();
                $_SESSION['student_name'] = $name;

                header("Location: index.php");
                exit;
            } catch (PDOException $e) {

                if ($e->getCode() == 23000) {
                    $error = 'This email is already registered.';
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Registration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Arial", sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
            position: relative;
            padding: 20px;
        }

        body::before,
        body::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            filter: blur(40px);
        }

        body::before {
            top: -80px;
            left: -80px;
        }

        body::after {
            bottom: -80px;
            right: -80px;
        }

        .container {
            width: 400px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: pop 0.5s ease;
        }

        @keyframes pop {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        h2 {
            margin-bottom: 10px;
            color: #fff;
            font-size: 28px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 25px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: none;
            border-radius: 10px;
            outline: none;
            background: rgba(255, 255, 255, 0.7);
            transition: 0.3s;
        }

        input:focus {
            transform: scale(1.02);
            background: white;
        }

        .submit {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #ff6f61, #ff9966);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
        }

        .submit:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .error-msg {
            background: rgba(255, 80, 80, 0.25);
            color: #7a0000;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 600;
        }

        .success-msg {
            background: rgba(80, 255, 80, 0.25);
            color: #006400;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 600;
        }

        .login-link {
            margin-top: 20px;
            color: white;
            font-size: 14px;
        }

        .login-link a {
            color: #fff;
            text-decoration: underline;
            font-weight: bold;
        }

        .login-link a:hover {
            color: #ff6f61;
        }

        .required {
            color: #ff6f61;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>📝 Create Account</h2>
        <div class="subtitle">Join us and start learning!</div>

        <?php if ($error): ?>
            <div class="error-msg">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-msg">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" id="registerForm">
            <input type="text" name="name" placeholder="Full Name *" required
                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" />

            <input type="email" name="email" placeholder="Email *" required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />

            <input type="password" name="password" placeholder="Password * (min. 4 characters)" required />

            <input type="number" name="age" placeholder="Age (optional)"
                value="<?= htmlspecialchars($_POST['age'] ?? '') ?>" />

            <button class="submit" type="submit">✨ Register Now</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <script>
        // Optional: Add password length validation on client side
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            if (password.length < 4) {
                e.preventDefault();
                alert('Password must be at least 4 characters long.');
            }
        });
    </script>
</body>

</html>
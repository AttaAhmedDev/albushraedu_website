<?php
session_start();
require_once 'db.php';

$error    = '';
$lastRole = $_POST['role'] ?? 'student';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $role     = $_POST['role']     ?? '';
  $email    = trim($_POST['email']    ?? '');
  $password = trim($_POST['password'] ?? '');

  // ── التحقق من البريد الإلكتروني فقط في البداية ──
  if (empty($email)) {
    $error = 'Please enter your email.';
  } elseif ($role === 'admin') {

    // ── Admin Login ──────────────────────────────
    if (empty($password)) {
      $error = 'Please enter your password.';
    } else {
      $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
      $stmt->execute([$email]);
      $admin = $stmt->fetch();

      if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['role']       = 'admin';
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['email']      = $admin['email'];
        $_SESSION['admin_logged_in'] = true;  // ✅ أضف هذا السطر
        header("Location: index.php");
        exit;
      } else {
        $error = 'Wrong admin email or password.';
      }
    }
  } elseif ($role === 'student') {

    // ── Student Login (email + password only) ──
    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $student = $stmt->fetch();

    if (!$student) {
      $error = 'No student found with this email.';
    } elseif (empty($student['password'])) {
      $error = 'Account setup incomplete. Please contact admin.';
    } elseif (empty($password)) {
      $error = 'Please enter your password.';
    } elseif (password_verify($password, $student['password'])) {
      $_SESSION['role']         = 'student';
      $_SESSION['student_id']   = $student['id'];
      $_SESSION['student_name'] = $student['name'];
      header("Location: index.php");
      exit;
    } else {
      $error = 'Wrong password.';
    }
  } else {
    $error = 'Invalid role selected.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kids Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Arial", sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #ffecd2, #fcb69f);
      overflow: hidden;
      position: relative;
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
      width: 380px;
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
      margin-bottom: 15px;
      color: #fff;
      font-size: 24px;
      text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .toggle {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .toggle button {
      width: 48%;
      padding: 10px;
      border: none;
      cursor: pointer;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.4);
      font-weight: bold;
      transition: 0.3s;
    }

    .toggle button.active {
      background: #ff6f61;
      color: white;
      transform: scale(1.05);
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
      margin-top: 10px;
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
      margin-bottom: 12px;
      font-size: 14px;
      font-weight: 600;
    }

    #studentFields {
      display: none;
    }
  </style>
</head>

<body>
  <div class="container">

    <h2 id="title"><?= $lastRole === 'admin' ? '🛠 Admin Login' : '👶 Student Login' ?></h2>

    <div class="toggle">
      <button type="button" id="studentBtn" <?= $lastRole !== 'admin' ? 'class="active"' : '' ?>>Student</button>
      <button type="button" id="adminBtn" <?= $lastRole === 'admin' ? 'class="active"' : '' ?>>Admin</button>
    </div>

    <?php if ($error): ?>
      <div class="error-msg">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="loginForm">
      <input type="hidden" name="role" id="role" value="<?= htmlspecialchars($lastRole) ?>" />

      <div id="studentFields" style="display:<?= $lastRole !== 'admin' ? 'block' : 'none' ?>;">
        <!-- حقول الاسم والعمر مخفية حالياً -->
      </div>

      <input type="email" name="email" placeholder="Email" required
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      <input type="password" name="password" placeholder="Password" required />

      <button class="submit" type="submit">🚀 Login</button>
    </form>

    <!-- زر العودة إلى الصفحة الرئيسية -->
    <div style="margin-top: 20px;">
      <a href="index.php" style="
          display: inline-block;
          padding: 10px 20px;
          background: rgba(255, 255, 255, 0.3);
          color: #fff;
          text-decoration: none;
          border-radius: 12px;
          font-weight: bold;
          transition: 0.3s;
          width: 100%;
          text-align: center;
        " onmouseover="this.style.background='rgba(255, 255, 255, 0.5)'"
        onmouseout="this.style.background='rgba(255, 255, 255, 0.3)'">
        🏠 Back to Home
      </a>
    </div>

  </div>
  <script>
    const studentBtn = document.getElementById("studentBtn");
    const adminBtn = document.getElementById("adminBtn");
    const studentFields = document.getElementById("studentFields");
    const roleInput = document.getElementById("role");
    const title = document.getElementById("title");

    studentBtn.onclick = () => {
      studentBtn.classList.add("active");
      adminBtn.classList.remove("active");
      studentFields.style.display = "block";
      roleInput.value = "student";
      title.innerText = "👶 Student Login";
    };

    adminBtn.onclick = () => {
      adminBtn.classList.add("active");
      studentBtn.classList.remove("active");
      studentFields.style.display = "none";
      roleInput.value = "admin";
      title.innerText = "🛠 Admin Login";
    };
  </script>
</body>

</html>
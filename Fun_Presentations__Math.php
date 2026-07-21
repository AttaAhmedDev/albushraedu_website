<?php
session_start();
require_once 'db.php';

// التحقق من حالة تسجيل الدخول
$isLoggedIn = isset($_SESSION['role']);
$userName = '';
$userRole = '';

if ($isLoggedIn) {
  $userName = $_SESSION['student_name'] ?? $_SESSION['admin_name'] ?? 'User';
  $userRole = $_SESSION['role'] ?? '';
}

// get files from database
$files = $pdo->query("SELECT * FROM math_presentation ORDER BY id DESC")->fetchAll();
$images = glob("images/cardImage/*.{jpg,jpeg,png,avif}", GLOB_BRACE);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <title>Albushra World | Math Worksheets</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Baloo+2:wght@500;600;700;800&family=Kalam:wght@300;400;700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    /* ---------- جميع الأنماط السابقة كما هي (لم يتم حذف أي شيء) ---------- */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Quicksand', 'Poppins', sans-serif;
      overflow-x: hidden;
      background-image: url("https://images.pexels.com/photos/30594011/pexels-photo-30594011.jpeg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 30px;
      background: rgba(255, 250, 240, 0.96);
      backdrop-filter: blur(8px);
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 3px solid #ffb347;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      flex-wrap: wrap;
      gap: 12px;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255, 235, 205, 0.5);
      padding: 5px 18px 5px 12px;
      border-radius: 60px;
    }

    .star {
      font-size: 32px;
      animation: starGlow 2.5s infinite alternate;
      filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.1));
    }

    @keyframes starGlow {
      0% {
        transform: rotate(0deg) scale(1);
        text-shadow: 0 0 0px gold;
      }

      100% {
        transform: rotate(8deg) scale(1.08);
        text-shadow: 0 0 8px #ffdd99;
      }
    }

    .logo h3 {
      font-family: 'Baloo 2', cursive;
      color: #e34d3b;
      font-size: 1.2rem;
      font-weight: 800;
      letter-spacing: -0.3px;
    }

    .logo p {
      font-size: 10px;
      color: #f4a261;
      font-weight: 700;
      background: #fff2e0;
      display: inline-block;
      padding: 2px 8px;
      border-radius: 40px;
    }

    nav {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    nav a {
      text-decoration: none;
      padding: 8px 10px;
      border-radius: 50px;
      background: rgba(255, 241, 220, 0.4);
      font-weight: 700;
      color: #4a3b2c;
      transition: 0.3s;
      font-family: 'Baloo 2', cursive;
      font-size: 0.85rem;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    nav a i,
    nav a span {
      font-size: 1rem;
    }

    nav a:hover {
      background: #ffd966;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .hamburger {
      display: none;
      font-size: 28px;
      cursor: pointer;
      background: #ffd9b5;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
      color: #c43a1b;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .hamburger:hover {
      background: #ffb66f;
      transform: scale(1.02);
    }

    .mobile-nav {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 245, 235, 0.98);
      backdrop-filter: blur(12px);
      z-index: 2000;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 28px;
      transform: translateX(100%);
      transition: transform 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
      padding: 2rem;
    }

    .mobile-nav.open {
      transform: translateX(0);
    }

    .mobile-nav a {
      font-size: 1.6rem;
      font-weight: 700;
      text-decoration: none;
      background: #ffd9b5;
      padding: 12px 28px;
      border-radius: 60px;
      width: 80%;
      text-align: center;
      font-family: 'Baloo 2', cursive;
      transition: 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      color: #4a2e1f;
    }

    .mobile-nav a i {
      font-size: 1.8rem;
    }

    .mobile-nav a:hover,
    .mobile-nav a:active {
      background: #ff9f4a;
      color: white;
      transform: scale(1.02);
    }

    .close-menu {
      position: absolute;
      top: 30px;
      right: 30px;
      font-size: 2.5rem;
      background: #ffb77c;
      width: 55px;
      height: 55px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: white;
      transition: 0.2s;
    }

    .close-menu:hover {
      background: #e55a2c;
      transform: rotate(90deg);
    }

    @media (max-width: 880px) {
      .navbar {
        padding: 12px 20px;
      }

      nav {
        display: none;
      }

      .hamburger {
        display: flex;
      }

      .logo h3 {
        font-size: 1rem;
      }
    }

    @media (max-width: 600px) {
      .logo {
        padding: 3px 12px;
      }

      .star {
        font-size: 28px;
      }

      .mobile-nav a {
        font-size: 1.2rem;
        padding: 10px 18px;
        width: 90%;
      }

      .mobile-nav a i {
        font-size: 1.3rem;
      }

      .close-menu {
        width: 45px;
        height: 45px;
        font-size: 2rem;
        top: 20px;
        right: 20px;
      }
    }

    .sightwords-section {
      max-width: 1300px;
      margin: 40px auto;
      padding: 0 24px;
    }

    .section-heading {
      text-align: center;
      font-size: 2.4rem;
      font-weight: 800;
      color: #d43f1d;
      margin-bottom: 35px;
      background: #fff4e6;
      display: inline-block;
      width: auto;
      padding: 10px 42px;
      border-radius: 70px;
      letter-spacing: -0.5px;
      box-shadow: 0 8px 14px rgba(0, 0, 0, 0.05);
      font-family: 'Baloo 2', cursive;
      border: 2px solid #ffc48a;
    }

    .heading-wrapper {
      text-align: center;
      margin-bottom: 20px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 28px;
      padding: 0;
    }

    .card {
      position: relative;
      height: 280px;
      border-radius: 25px;
      overflow: hidden;
      box-shadow: 0 18px 40px rgba(0, 0, 0, 0.15);
      transition: all 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1);
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 28px 36px -12px rgba(0, 0, 0, 0.3);
    }

    .card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.85);
      transition: transform 0.4s ease;
    }

    .card:hover img {
      transform: scale(1.1);
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.65), transparent 70%);
      z-index: 1;
    }

    .content {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      padding: 20px;
      color: white;
      z-index: 2;
      text-align: center;
    }

    .content h3 {
      font-size: 2rem;
      font-family: 'Baloo 2', cursive;
      margin: 8px 0;
      text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.3);
      letter-spacing: 1px;
    }

    .badge {
      background: #ffb347;
      padding: 5px 14px;
      border-radius: 40px;
      font-size: 12px;
      font-weight: 800;
      color: #3a2a1a;
      display: inline-block;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      font-family: 'Baloo 2', cursive;
    }

    /* مجموعة الأزرار (Download + View) */
    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-top: 12px;
      flex-wrap: wrap;
    }

    .content a,
    .content .view-btn {
      display: inline-block;
      padding: 8px 22px;
      background: #42b983;
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 800;
      font-family: 'Baloo 2', cursive;
      transition: all 0.25s ease-in-out;
      font-size: 0.9rem;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      border: none;
      cursor: pointer;
    }

    .content .view-btn {
      background: #5dade2;
      cursor: default;
      /* غير قابل للنقر - شكل فقط */
      opacity: 0.9;
    }

    .content a:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
    }

    /* تثبيت ألوان التمرير لزر التحميل فقط */
    .grid .card:nth-child(1) .content a:not(.view-btn):hover {
      background: #f39c12 !important;
      box-shadow: 0 8px 18px rgba(243, 156, 18, 0.4);
    }

    .grid .card:nth-child(2) .content a:not(.view-btn):hover {
      background: #e74c3c !important;
      box-shadow: 0 8px 18px rgba(231, 76, 60, 0.4);
    }

    .grid .card:nth-child(3) .content a:not(.view-btn):hover {
      background: #9b59b6 !important;
      box-shadow: 0 8px 18px rgba(155, 89, 182, 0.4);
    }

    .grid .card:nth-child(4) .content a:not(.view-btn):hover {
      background: #2980b9 !important;
      box-shadow: 0 8px 18px rgba(41, 128, 185, 0.4);
    }

    @media (max-width: 550px) {
      .grid {
        gap: 18px;
      }

      .card {
        height: 240px;
      }

      .content h3 {
        font-size: 1.6rem;
      }

      .section-heading {
        font-size: 1.8rem;
        padding: 6px 20px;
      }

      .sightwords-section {
        padding: 0 16px;
        margin-top: 20px;
      }

      .action-buttons {
        gap: 8px;
      }

      .content a,
      .content .view-btn {
        padding: 6px 14px;
        font-size: 0.75rem;
      }
    }

    .footer {
      background: #f07c5c;
      background-image: repeating-linear-gradient(45deg, #ffb47b10 0px, #ffb47b10 2px, transparent 2px, transparent 8px);
      color: #fff4e6;
      padding: 1.8rem 2rem 1rem;
      text-align: center;
      border-top: 8px solid #ffd966;
      margin-top: 60px;
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      gap: 1.5rem;
    }

    .footer-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255, 235, 200, 0.2);
      padding: 5px 20px;
      border-radius: 60px;
    }

    .footer-logo .star {
      font-size: 28px;
    }

    .footer-logo h4 {
      font-family: 'Kalam', cursive;
      font-size: 1.2rem;
      font-weight: 700;
    }

    .footer-social {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .footer-social a {
      color: #fff0da;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 210, 0.1);
      padding: 6px 16px;
      border-radius: 50px;
      transition: 0.25s;
    }

    .footer-social a:hover {
      background: #ffb347;
      color: #3a280f;
    }

    .footer-copyright {
      margin-top: 1.5rem;
      font-size: 0.7rem;
      opacity: 0.85;
      border-top: 2px dotted #ffcf9a;
      padding-top: 1rem;
    }

    @media (max-width: 780px) {
      .footer-container {
        flex-direction: column;
        text-align: center;
      }
    }

    /* مودال الألعاب */
    .game-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }

    .modal-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(6px);
      animation: fadeOverlay 0.3s ease;
    }

    @keyframes fadeOverlay {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .modal-container {
      position: relative;
      background: #fffef7;
      max-width: 460px;
      width: 90%;
      border-radius: 68px;
      padding: 1.8rem 1.5rem 2rem;
      text-align: center;
      box-shadow: 0 35px 55px -15px rgba(0, 0, 0, 0.3), inset 0 2px 4px rgba(255, 255, 200, 0.8);
      border: 3px solid #ffb347;
      animation: popModal 0.4s cubic-bezier(0.34, 1.3, 0.55, 1);
      z-index: 10000;
    }

    @keyframes popModal {
      0% {
        transform: scale(0.85);
        opacity: 0;
      }

      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    .modal-close {
      position: absolute;
      top: 16px;
      right: 22px;
      background: #ffe1c6;
      border: none;
      font-size: 28px;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      cursor: pointer;
      font-weight: bold;
      color: #e63946;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-close:hover {
      background: #ffb347;
      color: white;
      transform: rotate(90deg);
    }

    .modal-container h2 {
      font-size: 1.9rem;
      font-weight: 800;
      color: #e65c2e;
      margin: 0.5rem 0 0.2rem;
      font-family: 'Kalam', cursive;
    }

    .modal-container p {
      font-size: 1rem;
      color: #4a5b66;
      margin-bottom: 1.8rem;
      font-weight: 500;
    }

    .modal-options {
      display: flex;
      flex-direction: column;
      gap: 18px;
      margin-top: 10px;
    }

    .modal-option {
      border: none;
      padding: 16px 12px;
      font-size: 1.3rem;
      font-weight: 700;
      border-radius: 100px;
      cursor: pointer;
      transition: all 0.25s ease;
      background: #ffecdd;
      color: #b64926;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 14px;
      font-family: 'Quicksand', sans-serif;
      border: 2px solid #ffc896;
    }

    .modal-option i,
    .modal-option span {
      font-size: 1.8rem;
    }

    .english-option {
      background: #c7e9fb;
      border-color: #6bb5ff;
      color: #1f6392;
    }

    .english-option:hover {
      background: #a1d4ff;
      transform: translateY(-5px);
      box-shadow: 0 12px 20px -8px rgba(31, 99, 146, 0.3);
    }

    .math-option {
      background: #fee2b5;
      border-color: #ffa559;
      color: #aa4e1c;
    }

    .math-option:hover {
      background: #ffd699;
      transform: translateY(-5px);
      box-shadow: 0 12px 20px -8px rgba(170, 78, 28, 0.3);
    }

    /* مودال ورق العمل */
    .worksheets-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }

    .worksheets-modal .modal-container {
      border-color: #6c5ce7;
      background: #fff9f0;
    }

    .worksheets-modal h2 {
      color: #6c5ce7;
    }

    .worksheets-modal .modal-option {
      background: #f0e9ff;
      border-color: #a29bfe;
      color: #4a2e8a;
    }

    .worksheets-modal .modal-option:hover {
      background: #d4c7ff;
      transform: translateY(-5px);
      box-shadow: 0 12px 20px -8px rgba(108, 92, 231, 0.3);
    }

    /* مودال العروض التقديمية (Fun Presentations) */
    .presentations-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }

    .presentations-modal .modal-container {
      border-color: #e67e22;
      background: #fffaf2;
    }

    .presentations-modal h2 {
      color: #e67e22;
    }

    .presentations-modal .modal-option {
      background: #ffe8d4;
      border-color: #f39c12;
      color: #b64926;
    }

    .presentations-modal .modal-option:hover {
      background: #ffd9b5;
      transform: translateY(-5px);
      box-shadow: 0 12px 20px -8px rgba(230, 126, 34, 0.3);
    }
  </style>
</head>

<body>

  <header class="navbar">
    <div class="logo">
      <span class="star">🌟</span>
      <div>
        <h3>Albushra's World</h3>
        <p>✨ kinder joy ✨</p>
      </div>
    </div>
    <nav id="desktopNav">
      <a href="index.php">🏠 Home</a>
      <a href="Worksheets.php" class="worksheets-link-trigger">📄 Worksheets</a>
      <a href="online_games.php" class="game-link-trigger">🎮 Online Games</a>
      <a href="#" class="presentations-trigger">🎭 Fun Presentations</a>
      <a href="english_flashcards.php">📘 English Flashcards</a>
      <a href="math_flashcards.php">🧮 Math Flashcards</a>
      <?php if ($isLoggedIn && $userRole === 'admin'): ?>
        <a href="admin_dashboard.php" id="dashboardLink">🛠 Dashboard</a>
      <?php endif; ?>
      <?php if ($isLoggedIn): ?>
        <a href="logout.php" id="logoutBtn">🚪 Logout</a>
      <?php else: ?>
        <a href="login.php" id="loginBtn">🔐 Login</a>
        <a href="register.php">📝 Register</a>
      <?php endif; ?>
    </nav>
    <div class="hamburger" id="hamburgerIcon"><i class="fas fa-bars"></i></div>
  </header>

  <div class="mobile-nav" id="mobileNavMenu">
    <div class="close-menu" id="closeMenuBtn"><i class="fas fa-times"></i></div>
    <a href="index.php">🏠 Home</a>
    <a href="Worksheets.php" class="worksheets-link-trigger">📄 Worksheets</a>
    <a href="online_games.php" class="game-link-trigger">🎮 Online Games</a>
    <a href="#" class="presentations-trigger">🎭 Fun Presentations</a>
    <a href="english_flashcards.php">📘 English Flashcards</a>
    <a href="math_flashcards.php">🧮 Math Flashcards</a>
    <?php if ($isLoggedIn && $userRole === 'admin'): ?>
      <a href="admin_dashboard.php" id="dashboardLink">🛠 Dashboard</a>
    <?php endif; ?>
    <?php if ($isLoggedIn): ?>
      <a href="logout.php" id="logoutbtn">🚪 Logout</a>
    <?php else: ?>
      <a href="login.php" id="loginbtn">🔐 Login</a>
      <a href="register.php">📝 Register</a>
    <?php endif; ?>
  </div>

  <div class="sightwords-section">
    <div class="heading-wrapper">
      <div class="section-heading">📖 Fun Presentations Math</div>
    </div>
    <div class="grid">
      <?php foreach ($files as $index => $file): ?>
        <?php $img = $images[$index % count($images)]; ?>
        <div class="card">
          <img src="<?= $img ?>" alt="card image" />
          <div class="overlay"></div>
          <div class="content">
            <div class=" action-buttons">
              <a href="download_files.php?table=math_presentation&id=<?= $file['id'] ?>"> Download</a>
            </div>
          </div>
        </div>
    </div>
  <?php endforeach; ?>
  </div>
  </div>

  <!-- مودال الألعاب -->
  <div id="gameModal" class="game-modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
      <button class="modal-close" id="closeGameModalBtn">&times;</button>
      <h2>🎮 Choose your game!</h2>
      <p>Let's play and learn with fun!</p>
      <div class="modal-options">
        <button id="englishGamesModalBtn" class="modal-option english-option"><i class="fas fa-book-open"></i> 📖 English Online Games</button>
        <button id="mathGamesModalBtn" class="modal-option math-option"><i class="fas fa-calculator"></i> 🧮 Math Online Games</button>
      </div>
    </div>
  </div>

  <!-- مودال ورق العمل -->
  <div id="worksheetsModal" class="worksheets-modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
      <button class="modal-close" id="closeWorksheetsModalBtn">&times;</button>
      <h2>📚 Choose Worksheets</h2>
      <p>Select the type of worksheets you need!</p>
      <div class="modal-options">
        <button id="wordFamiliesBtn" class="modal-option"><i class="fas fa-font"></i> Word Families Worksheets</button>
        <button id="sightWordsBtn" class="modal-option"><i class="fas fa-eye"></i> Sight Words Worksheets</button>
        <button id="mathWorksheetsBtn" class="modal-option"><i class="fas fa-calculator"></i> Math Worksheets</button>
        <button id="englishWorksheetsBtn" class="modal-option"><i class="fas fa-book"></i> English Worksheets</button>
      </div>
    </div>
  </div>

  <!-- مودال العروض التقديمية (Fun Presentations) -->
  <div id="presentationsModal" class="presentations-modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
      <button class="modal-close" id="closePresentationsModalBtn">&times;</button>
      <h2>🎭 Fun Presentations</h2>
      <p>Choose a presentation topic!</p>
      <div class="modal-options">
        <button id="wordFamiliesPresBtn" class="modal-option"><i class="fas fa-font"></i> Word Families</button>
        <button id="sightWordsPresBtn" class="modal-option"><i class="fas fa-eye"></i> Sight Words</button>
        <button id="mathPresBtn" class="modal-option"><i class="fas fa-calculator"></i> Math</button>
        <button id="englishPresBtn" class="modal-option"><i class="fas fa-book"></i> English</button>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    // ----- القائمة المتنقلة (Mobile Menu) -----
    const hamburger = document.getElementById("hamburgerIcon");
    const mobileMenu = document.getElementById("mobileNavMenu");
    const closeMenuBtn = document.getElementById("closeMenuBtn");

    function openMobileMenu() {
      mobileMenu.classList.add("open");
      document.body.style.overflow = "hidden";
    }

    function closeMobileMenu() {
      mobileMenu.classList.remove("open");
      document.body.style.overflow = "";
    }
    if (hamburger) hamburger.addEventListener("click", openMobileMenu);
    if (closeMenuBtn) closeMenuBtn.addEventListener("click", closeMobileMenu);
    const mobileLinks = mobileMenu.querySelectorAll("a");
    mobileLinks.forEach(link => {
      link.addEventListener("click", () => {
        closeMobileMenu();
      });
    });

    // ----- مودال الألعاب (Online Games) -----
    const gameLinks = document.querySelectorAll('.game-link-trigger');
    const gameModal = document.getElementById('gameModal');
    const closeGameModalBtn = document.getElementById('closeGameModalBtn');
    const gameModalOverlay = document.querySelector('#gameModal .modal-overlay');
    const englishBtn = document.getElementById('englishGamesModalBtn');
    const mathBtn = document.getElementById('mathGamesModalBtn');

    function openGameModal(event) {
      if (event) event.preventDefault();
      if (gameModal) gameModal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      console.log("Game modal opened");
    }

    function closeGameModal() {
      if (gameModal) gameModal.style.display = 'none';
      document.body.style.overflow = '';
    }
    gameLinks.forEach(link => {
      link.addEventListener('click', openGameModal);
    });
    if (closeGameModalBtn) closeGameModalBtn.addEventListener('click', closeGameModal);
    if (gameModalOverlay) gameModalOverlay.addEventListener('click', closeGameModal);
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && gameModal && gameModal.style.display === 'flex') closeGameModal();
    });
    if (englishBtn) englishBtn.addEventListener('click', () => {
      window.location.href = 'english_online_games.php';
    });
    if (mathBtn) mathBtn.addEventListener('click', () => {
      window.location.href = 'math_online_games.php';
    });

    // ----- مودال ورق العمل (Worksheets) -----
    const worksheetsLinks = document.querySelectorAll('.worksheets-link-trigger');
    const worksheetsModal = document.getElementById('worksheetsModal');
    const closeWorksheetsModalBtn = document.getElementById('closeWorksheetsModalBtn');
    const worksheetsModalOverlay = document.querySelector('#worksheetsModal .modal-overlay');

    console.log("عدد روابط Worksheets:", worksheetsLinks.length);
    console.log("عنصر المودال:", worksheetsModal);

    function openWorksheetsModal(event) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
      if (worksheetsModal) {
        worksheetsModal.style.display = 'flex';
        console.log("تم فتح مودال ورق العمل");
      } else {
        console.error("لم يتم العثور على عنصر المودال (#worksheetsModal)");
      }
      document.body.style.overflow = 'hidden';
    }

    function closeWorksheetsModal() {
      if (worksheetsModal) worksheetsModal.style.display = 'none';
      document.body.style.overflow = '';
      console.log("تم إغلاق المودال");
    }

    if (worksheetsLinks.length > 0) {
      worksheetsLinks.forEach(link => {
        link.addEventListener('click', openWorksheetsModal);
      });
    } else {
      console.warn("لم يتم العثور على أي رابط يحمل class='worksheets-link-trigger'");
    }

    if (closeWorksheetsModalBtn) closeWorksheetsModalBtn.addEventListener('click', closeWorksheetsModal);
    if (worksheetsModalOverlay) worksheetsModalOverlay.addEventListener('click', closeWorksheetsModal);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && worksheetsModal && worksheetsModal.style.display === 'flex') {
        closeWorksheetsModal();
      }
    });

    // أزرار الاختيار داخل مودال ورق العمل
    const wordFamiliesBtn = document.getElementById('wordFamiliesBtn');
    const sightWordsBtn = document.getElementById('sightWordsBtn');
    const mathWorksheetsBtn = document.getElementById('mathWorksheetsBtn');
    const englishWorksheetsBtn = document.getElementById('englishWorksheetsBtn');

    if (wordFamiliesBtn) {
      wordFamiliesBtn.addEventListener('click', () => {
        window.location.href = 'Word_Families_worksheets.php';
      });
    }
    if (sightWordsBtn) {
      sightWordsBtn.addEventListener('click', () => {
        window.location.href = 'Sight_words_Worksheets.php';
      });
    }
    if (mathWorksheetsBtn) {
      mathWorksheetsBtn.addEventListener('click', () => {
        window.location.href = 'Math_Worksheets.php';
      });
    }
    if (englishWorksheetsBtn) {
      englishWorksheetsBtn.addEventListener('click', () => {
        window.location.hroef = 'English_Worksheets.php';
      });
    }

    // ----- مودال العروض التقديمية (Fun Presentations) -----
    const presentationsTriggers = document.querySelectorAll('.presentations-trigger');
    const presentationsModal = document.getElementById('presentationsModal');
    const closePresentationsModalBtn = document.getElementById('closePresentationsModalBtn');
    const presentationsModalOverlay = document.querySelector('#presentationsModal .modal-overlay');

    function openPresentationsModal(event) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
      if (presentationsModal) {
        presentationsModal.style.display = 'flex';
        console.log("تم فتح مودال العروض التقديمية");
      } else {
        console.error("لم يتم العثور على عنصر المودال (#presentationsModal)");
      }
      document.body.style.overflow = 'hidden';
    }

    function closePresentationsModal() {
      if (presentationsModal) presentationsModal.style.display = 'none';
      document.body.style.overflow = '';
      console.log("تم إغلاق مودال العروض التقديمية");
    }

    if (presentationsTriggers.length > 0) {
      presentationsTriggers.forEach(trigger => {
        trigger.addEventListener('click', openPresentationsModal);
      });
    } else {
      console.warn("لم يتم العثور على أي رابط يحمل class='presentations-trigger'");
    }

    if (closePresentationsModalBtn) closePresentationsModalBtn.addEventListener('click', closePresentationsModal);
    if (presentationsModalOverlay) presentationsModalOverlay.addEventListener('click', closePresentationsModal);
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && presentationsModal && presentationsModal.style.display === 'flex') {
        closePresentationsModal();
      }
    });

    // أزرار الاختيار داخل مودال العروض التقديمية
    const wordFamiliesPresBtn = document.getElementById('wordFamiliesPresBtn');
    const sightWordsPresBtn = document.getElementById('sightWordsPresBtn');
    const mathPresBtn = document.getElementById('mathPresBtn');
    const englishPresBtn = document.getElementById('englishPresBtn');

    if (wordFamiliesPresBtn) {
      wordFamiliesPresBtn.addEventListener('click', () => {
                window.location.href = 'Fun_Presentations_word_Families.php';

      });
    }
    if (sightWordsPresBtn) {
      sightWordsPresBtn.addEventListener('click', () => {
         window.location.href = 'Fun_Presentations__Sight_words.php';

      });
    }
    if (mathPresBtn) {
      mathPresBtn.addEventListener('click', () => {
                            window.location.href = 'Fun_Presentations__Math.php';

      });
    }
    if (englishPresBtn) {
      englishPresBtn.addEventListener('click', () => {
                            window.location.href = 'Fun_Presentations_English.php';

      });
    }
  </script>
</body>

</html>
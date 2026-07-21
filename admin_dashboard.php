<?php
session_start();
require_once 'db.php';

// التحقق من حالة تسجيل الدخول (يجب أن يكون admin)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}


class FileRepository
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function getAll($table)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM `$table` ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
$repo = new FileRepository($pdo);
$games = $repo->getAll('game_settings');
$mathGames = $repo->getAll('math_settings');
$files_english_worksheet = $repo->getAll('english_worksheet');
$files_math_worksheet = $repo->getAll('math_worksheet');
$files_sight_worksheet = $repo->getAll('sight_worksheet');
$files_word_worksheet = $repo->getAll('word_worksheet');
$files_word_presentation = $repo->getAll('word_presentation');
$files_english_presentation = $repo->getAll('english_presentation');
$files_math_presentation = $repo->getAll('math_presentation');
$files_sight_presentation = $repo->getAll('sight_presentation');
$files_english_flashcards = $repo->getAll('letters');
$files_math_flashcards = $repo->getAll('numbers');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Advanced Admin Dashboard</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    /* style for select element in english flashcards */
    .select-wrapper {
      position: relative;
      width: 100%;
      margin: 10px 0;
    }

    .letter-select {
      width: 100%;
      padding: 12px 15px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 12px;
      border: 2px solid #ddd;
      background: linear-gradient(135deg, #ff6fa3, #a78bfa);
      cursor: pointer;
      appearance: none;
      outline: none;
      transition: 0.3s;
    }

    /* hover */
    .letter-select:hover {
      border-color: #4facfe;
    }

    /* focus */
    .letter-select:focus {
      border-color: #4facfe;
      box-shadow: 0 0 10px rgba(79, 172, 254, 0.4);
    }

    /* arrow custom */
    .select-wrapper::after {
      content: "▼";
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: #555;
      font-size: 12px;
    }

    /* File Input Hidden */
    input[type="file"] {
      display: none;
    }

    /* Custom Label for input file */
    .file-label {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 10px;
      border-radius: 12px;
      border: 2px dashed #f9a8c9;
      background: rgba(255, 77, 136, 0.05);
      cursor: pointer;
      transition: .3s;
    }

    .file-label:hover {
      border-color: #ff4d88;
      background: rgba(255, 77, 136, 0.1);
    }

    .file-icon {
      font-size: 1.3rem;
    }

    .file-text {
      font-size: 0.88rem;
      color: #aaa;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .file-text.selected {
      color: #ff4d88;
      font-weight: 600;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background: linear-gradient(135deg, #ffe6f0, #f3e8ff);
    }

    /* 🌸 SIDEBAR */
    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, #ff6fa3, #a78bfa);
      color: white;
      padding: 20px;
      transition: .3s;
      box-shadow: 10px 0 25px rgba(0, 0, 0, .08);
      overflow-y: auto;
      max-height: 100%;
    }

    .sidebar h2 {
      margin-bottom: 30px;
      font-size: 22px;
    }

    .sidebar button {
      width: 100%;
      padding: 12px;
      margin-bottom: 12px;
      border: none;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      cursor: pointer;
      transition: .3s;
      font-weight: 600;
      backdrop-filter: blur(10px);
      text-align: left;
    }

    .sidebar button:hover {
      background: white;
      color: #ff4d88;
      transform: translateX(5px);
    }

    /* 📱 MENU TOGGLE */
    .menu-toggle {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      background: #ff4d88;
      color: white;
      border: none;
      padding: 10px 12px;
      border-radius: 10px;
      z-index: 999;
    }

    /* MAIN */
    .main {
      flex: 1;
      padding: 25px;
      min-width: 0;
      /* يمنع overflow */
    }

    .header {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(10px);
      padding: 20px;
      border-radius: 18px;
      margin-bottom: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
    }

    .header h2 {
      color: #ff4d88;
    }

    /* GRID */
    .grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    /* 🌸 CARDS */
    .card {
      background: rgba(255, 255, 255, 0.75);
      backdrop-filter: blur(12px);
      padding: 20px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
      transition: .3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    /* INPUTS */
    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 10px;
      border-radius: 12px;
      border: 1px solid #eee;
      outline: none;
      transition: .3s;
    }

    input:focus {
      border-color: #ff4d88;
      box-shadow: 0 0 8px rgba(255, 77, 136, 0.2);
    }

    /* 🌸 BUTTON */
    .btn {
      background: linear-gradient(135deg, #ff4d88, #a78bfa);
      color: white;
      border: none;
      padding: 12px;
      border-radius: 12px;
      cursor: pointer;
      width: 100%;
      font-weight: 600;
      transition: .3s;
    }

    .btn:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 20px rgba(255, 77, 136, 0.3);
    }

    /* LIST */
    .list-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
      background: #fff;
      padding: 10px 14px;
      border-radius: 12px;
      margin-top: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, .05);
      border-bottom: 1px solid #eee;
    }

    .list-item>div {
      flex: 1;
      min-width: 0;
    }

    .list-item>div span {
      display: block;
      font-weight: 600;
      color: #333;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .list-item>div small {
      display: block;
      color: #999;
      font-size: 0.78rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-top: 2px;
    }

    .btn-delete {
      flex-shrink: 0;
      background: #dc3545;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 0.82rem;
      white-space: nowrap;
      transition: .3s;
    }

    .btn-delete:hover {
      background: #c82333;
      transform: scale(1.05);
    }

    .preview-card {
      margin-top: 15px;
      padding: 15px;
      border-radius: 20px;
      background: linear-gradient(135deg, #ffb6c1, #c084fc);
      color: white;
      text-align: center;
      animation: fadeIn .4s ease;
    }

    .preview-card img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      margin-bottom: 10px;
      border: 2px solid white;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.9)
      }

      to {
        opacity: 1;
        transform: scale(1)
      }
    }

    .section {
      display: none
    }

    .active {
      display: block
    }

    .loading,
    .empty,
    .error {
      padding: 20px;
      text-align: center;
      color: #666;
    }

    .error {
      color: #dc3545;
    }

    /* Responsive */
    @media (max-width: 900px) {
      .grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }

      .menu-toggle {
        display: block;
      }

      .sidebar {
        position: fixed;
        left: -260px;
        top: 0;
        height: 100%;
        z-index: 998;
      }

      .sidebar.open {
        left: 0;
      }

      .main {
        padding: 70px 15px 20px;
      }

      .card {
        padding: 15px;
      }

      .list-item {
        flex-wrap: wrap;
      }

      .list-item>div {
        width: calc(100% - 90px);
      }
    }

    @media (max-width: 380px) {
      .list-item {
        flex-direction: column;
        align-items: flex-start;
      }

      .btn-delete {
        align-self: flex-end;
      }
    }
  </style>
</head>

<body>
  <button class="menu-toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></button>
  <div class="sidebar" id="sidebar">
    <h2>⚙ Admin</h2>
    <button onclick="show('games')">🎮 Games for English</button>
    <button onclick="show('math')">🎮 Games for Math</button>
    <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.3);">
    <button onclick="show('pdfs')">📄 English Worksheets</button>
    <button onclick="show('mathWorksheetsSection')">📐 Math Worksheets (PDF)</button>
    <button onclick="show('sightWordsWorksheetsSection')">👁️ Sight Worksheets (PDF)</button>
    <button onclick="show('wordFamiliesWorksheetsSection')">🔤 Word Worksheets (PDF)</button>
    <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.3);">
    <button onclick="show('fpWordFamiliesSection')">🎭 Presentations word (PPTX)</button>
    <button onclick="show('fpEnglishSection')">🎭 Presentations English (PPTX)</button>
    <button onclick="show('fpSightWordsSection')">🎭 Presentations Sight words (PPTX)</button>
    <button onclick="show('fpMathSection')">🎭 Presentations Math (PPTX)</button>
    <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.3);">
    <!-- الأزرار الجديدة للـ Flashcards -->
    <button onclick="show('englishFlashcardsSection')">📘 English Flashcards (PDF)</button>
    <button onclick="show('mathFlashcardsSection')">🧮 Math Flashcards (PDF)</button>
    <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.3);">
    <button onclick="show('footer')">🦶 Footer</button>
    <button onclick="show('changeAdminSection')">🔐 Change Admin</button>
    <button onclick="go()">🏠 Back to Homepage</button>
  </div>

  <div class="main">

    <div class="header">
      <h2>Dashboard</h2>
    </div>
    <!-- ========== الأقسام الأصلية ========== -->
    <div id="games" class="section active">
      <div class="grid">
        <div class="card">
          <h3>Add English Game</h3>
          <input id="gName" placeholder="Game Name">
          <input id="gLink" placeholder="Game Link">
          <button class="btn" onclick="saveGame()">Add English Game</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 English Games List</h3>
        <div id="englishList">
          <?php if (count($games) > 0): ?>
            <?php foreach ($games as $game): ?>
              <div class="list-item" data-id="<?= $game['id'] ?>">
                <div><span><?= htmlspecialchars($game['gName']) ?></span><br><small><?= htmlspecialchars($game['gLink']) ?></small></div>
                <button class="btn-delete" data-type="english" data-id="<?= $game['id'] ?>">🗑 Delete</button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No games found</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div id="math" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add Math Game</h3>
          <input id="mName" placeholder="Game Name">
          <input id="mLink" placeholder="Game Link">
          <button class="btn" onclick="saveMathGame()">Add Math Game</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 Math Games List</h3>
        <div id="mathList">
          <?php if (count($mathGames) > 0): ?>
            <?php foreach ($mathGames as $game): ?>
              <div class="list-item" data-id="<?= $game['id'] ?>">
                <div><span><?= htmlspecialchars($game['mName']) ?></span><br><small><?= htmlspecialchars($game['mLink']) ?></small></div>
                <button class="btn-delete" data-type="math" data-id="<?= $game['id'] ?>">🗑 Delete</button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No games found</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- english worksheet -->
    <div id="pdfs" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add English Worksheets (PDF)</h3>
          <input id="fileTitle" placeholder="File Title">
          <label for="fileInput" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="fileText">Choose PDF file...</span>
          </label>
          <input id="fileInput" type="file" accept=".pdf" onchange="updateFileName(this)">
          <button class="btn" onclick="savefile_english_worksheet()">Add File</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 Files List</h3>
        <div id="filesList">
          <?php if (count($files_english_worksheet) > 0): ?>
            <?php foreach ($files_english_worksheet as $file): ?>
              <div class="list-item" data-id="<?= $file['id'] ?>">
                <div>
                  <?php
                  $fileName  = basename($file['file_path']);
                  $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                  ?>
                  <span><?= htmlspecialchars($file['title']) ?>.<?= strtoupper($extension) ?></span>
                  <small><?= htmlspecialchars($file['file_path']) ?></small>
                </div>
                <button class="btn-delete" data-type="pdf" data-id="<?= $file['id'] ?>" onclick="deleteFile_english_worksheet(<?= $file['id'] ?>)">🗑 Delete</button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No files found</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- edit admin password and email -->
    <div id="changeAdminSection" class="section">
      <div class="card">
        <h3>🔐 Change Admin Credentials</h3>

        <input type="email" id="newEmail" placeholder="New Email">
        <input type="password" id="currentPassword" placeholder="Current Password">
        <input type="password" id="newPassword" placeholder="New Password">

        <button class="btn" onclick="updateAdmin()">Update</button>
        <p id="changeMsg"></p>
      </div>
    </div>

    <!-- edit footer -->
    <div id="footer" class="section">
      <div class="card" style="width: 50%;">
        <h3>Edit Footer</h3>
        <input id="insta" placeholder="Instagram">
        <input id="email" placeholder="Email">
        <input id="phone" placeholder="Phone">
        <button class="btn" onclick="saveFooter()">Save</button>
      </div>
    </div>
    <!-- edit math worksheets -->
    <div id="mathWorksheetsSection" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add Math Worksheet (PDF)</h3>
          <input type="text" id="mathWsTitle" placeholder="Worksheet Title">
          <label for="mathWsFile" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="mathWsFileText">Choose PDF file...</span>
          </label>
          <input type="file" id="mathWsFile" accept=".pdf" onchange="updateFileNameCustom('mathWsFile', 'mathWsFileText')">
          <button class="btn" onclick="addMathWorksheet()">Add Math Worksheet</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 Files List</h3>
        <div id="mathWorksheetsList">
          <?php foreach ($files_math_worksheet as $item): ?>
            <div class="list-item" data-id="<?= $item['id'] ?>">
              <div><span><?= htmlspecialchars($item['title']) ?></span><br><small><?= htmlspecialchars($item['file_path']) ?></small></div>
              <button class="btn-delete" onclick="deleteMathWorksheet(<?= $item['id'] ?>)">🗑 Delete</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <!-- Sight Words Worksheets (PDF فقط) -->
    <div id="sightWordsWorksheetsSection" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add Sight Words Worksheet (PDF)</h3>
          <input type="text" id="sightWsTitle" placeholder="Worksheet Title">
          <label for="sightWsFile" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="sightWsFileText">Choose PDF file...</span>
          </label>
          <input type="file" id="sightWsFile" accept=".pdf" onchange="updateFileNameCustom('sightWsFile', 'sightWsFileText')">
          <button class="btn" onclick="addSightWordsWorksheet()">Add Sight Words Worksheet</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>Sight Words Worksheets</h3>
        <div id="sightWordsWorksheetsList">
          <?php foreach ($files_sight_worksheet as $item): ?>
            <div class="list-item" data-id="<?= $item['id'] ?>">
              <div><span><?= htmlspecialchars($item['title']) ?></span><br><small><?= htmlspecialchars($item['file_path']) ?></small></div>
              <button class="btn-delete" onclick="deleteSightWordsWorksheet(<?= $item['id'] ?>)">🗑 Delete</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <!-- Word Families Worksheets (PDF فقط) -->
    <div id="wordFamiliesWorksheetsSection" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add Word Families Worksheet (PDF)</h3>
          <input type="text" id="wordFamiliesWsTitle" placeholder="Worksheet Title">
          <label for="wordFamiliesWsFile" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="wordFamiliesWsFileText">Choose PDF file...</span>
          </label>
          <input type="file" id="wordFamiliesWsFile" accept=".pdf" onchange="updateFileNameCustom('wordFamiliesWsFile', 'wordFamiliesWsFileText')">
          <button class="btn" onclick="addWordFamiliesWorksheet()">Add Word Families Worksheet</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 Word Families Worksheets List</h3>
        <div id="wordFamiliesWorksheetsList">
          <?php foreach ($files_word_worksheet as $item): ?>
            <div class="list-item" data-id="<?= $item['id'] ?>">
              <div><span><?= htmlspecialchars($item['title']) ?></span><br><small><?= htmlspecialchars($item['file_path']) ?></small></div>
              <button class="btn-delete" onclick="deleteWordFamiliesWorksheet(<?= $item['id'] ?>)">🗑 Delete</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <!-- ========== Fun Presentations (PPTX فقط) ========== -->
    <!-- Word Families Presentations (PPTX) -->
    <div id="fpWordFamiliesSection" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add Fun Presentation - Word Families (PPTX)</h3>
          <input type="text" id="fpWordFamiliesTitle" placeholder="Presentation Title">
          <label for="fpWordFamiliesFile" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="fpWordFamiliesFileText">Choose PPTX file...</span>
          </label>
          <input type="file" id="fpWordFamiliesFile" accept=".pptx" onchange="updateFileNameCustom('fpWordFamiliesFile', 'fpWordFamiliesFileText')">
          <button class="btn" onclick="addFunPresentation('word_families')">Add Presentation</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 Fun Presentations - Word Families List</h3>
        <div id="fpWordFamiliesList">
          <?php foreach ($files_word_presentation as $item): ?>
            <div class="list-item" data-id="<?= $item['id'] ?>">
              <div><span><?= htmlspecialchars($item['title']) ?></span><br><small><?= htmlspecialchars($item['file_path']) ?></small></div>
              <button class="btn-delete" onclick="deleteFunPresentation('word_families', <?= $item['id'] ?>)">🗑 Delete</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>


    <!-- English Presentations (PPTX) -->
    <div id="fpEnglishSection" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add Fun Presentation - English (PPTX)</h3>
          <input type="text" id="fpEnglishTitle" placeholder="Presentation Title">
          <label for="fpEnglishFile" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="fpEnglishFileText">Choose PPTX file...</span>
          </label>
          <input type="file" id="fpEnglishFile" accept=".pptx" onchange="updateFileNameCustom('fpEnglishFile', 'fpEnglishFileText')">
          <button class="btn" onclick="addFunPresentation('english')">Add Presentation</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 Fun Presentations - English List</h3>
        <div id="fpEnglishList">
          <?php foreach ($files_english_presentation as $item): ?>
            <div class="list-item" data-id="<?= $item['id'] ?>">
              <div><span><?= htmlspecialchars($item['title']) ?></span><br><small><?= htmlspecialchars($item['file_path']) ?></small></div>
              <button class="btn-delete" onclick="deleteFunPresentation('english', <?= $item['id'] ?>)">🗑 Delete</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Sight Words Presentations (PPTX) -->
    <div id="fpSightWordsSection" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add Fun Presentation - Sight Words (PPTX)</h3>
          <input type="text" id="fpSightWordsTitle" placeholder="Presentation Title">
          <label for="fpSightWordsFile" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="fpSightWordsFileText">Choose PPTX file...</span>
          </label>
          <input type="file" id="fpSightWordsFile" accept=".pptx" onchange="updateFileNameCustom('fpSightWordsFile', 'fpSightWordsFileText')">
          <button class="btn" onclick="addFunPresentation('sight_words')">Add Presentation</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 Fun Presentations - Sight Words List</h3>
        <div id="fpSightWordsList">
          <?php foreach ($files_sight_presentation as $item): ?>
            <div class="list-item" data-id="<?= $item['id'] ?>">
              <div><span><?= htmlspecialchars($item['title']) ?></span><br><small><?= htmlspecialchars($item['file_path']) ?></small></div>
              <button class="btn-delete" onclick="deleteFunPresentation('sight_words', <?= $item['id'] ?>)">🗑 Delete</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Math Presentations (PPTX) -->
    <div id="fpMathSection" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add Fun Presentation - Math (PPTX)</h3>
          <input type="text" id="fpMathTitle" placeholder="Presentation Title">
          <label for="fpMathFile" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="fpMathFileText">Choose PPTX file...</span>
          </label>
          <input type="file" id="fpMathFile" accept=".pptx" onchange="updateFileNameCustom('fpMathFile', 'fpMathFileText')">
          <button class="btn" onclick="addFunPresentation('math')">Add Presentation</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 Fun Presentations - Math List</h3>
        <div id="fpMathList">
          <?php foreach ($files_math_presentation as $item): ?>
            <div class="list-item" data-id="<?= $item['id'] ?>">
              <div><span><?= htmlspecialchars($item['title']) ?></span><br><small><?= htmlspecialchars($item['file_path']) ?></small></div>
              <button class="btn-delete" onclick="deleteFunPresentation('math', <?= $item['id'] ?>)">🗑 Delete</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ========== أقسام Flashcards الجديدة (PDF فقط) ========== -->
    <!-- English Flashcards -->
    <div id="englishFlashcardsSection" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add English Flashcard (PDF)</h3>
          <input type="text" id="englishFcTitle" placeholder="Flashcard Title">
          <div class="select-wrapper">
            <select id="englishFcLetter" class="input letter-select">
              <option value="">Select Letter</option>
              <?php foreach (range('A', 'Z') as $letter): ?>
                <option value="<?= $letter ?>"><?= $letter ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <label for="englishFcFile" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="englishFcFileText">Choose PDF file...</span>
          </label>
          <input type="file" id="englishFcFile" accept=".pdf"
            onchange="updateFileNameCustom('englishFcFile', 'englishFcFileText')">
          <button class="btn" onclick="addEnglishFlashcard()">Add English Flashcard</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 English Flashcards List</h3>
        <div id="englishFlashcardsList">
          <?php foreach ($files_english_flashcards as $item): ?>
            <div class="list-item" data-id="<?= $item['id'] ?>">
              <div><span><?= htmlspecialchars($item['file_name']) ?> ( letter : <?= $item['letter'] ?>)
                </span><br><small><?= htmlspecialchars($item['file_path']) ?></small></div>
              <button class="btn-delete" onclick="deleteEnglishFlashcard(<?= $item['id'] ?>)">🗑 Delete</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Math Flashcards -->
    <div id="mathFlashcardsSection" class="section">
      <div class="grid">
        <div class="card">
          <h3>Add Math Flashcard (PDF)</h3>
          <input type="text" id="mathFcTitle" placeholder="Flashcard Title">
          <div class="select-wrapper">
            <select id="mathFcNumber" class="input letter-select">
              <option value="">Select Number</option>
              <?php foreach (range(1, 20) as $number): ?>
                <option value="<?= $number ?>"><?= $number ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <label for="mathFcFile" class="file-label">
            <span class="file-icon">📎</span>
            <span class="file-text" id="mathFcFileText">Choose PDF file...</span>
          </label>
          <input type="file" id="mathFcFile" accept=".pdf"
            onchange="updateFileNameCustom('mathFcFile', 'mathFcFileText')">
          <button class="btn" onclick="addMathFlashcard()">Add Math Flashcard</button>
        </div>
      </div>
      <br>
      <div class="card">
        <h3>📋 Math Flashcards List</h3>
        <div id="mathFlashcardsList">
          <?php foreach ($files_math_flashcards as $item): ?>
            <div class="list-item" data-id="<?= $item['id'] ?>">
              <div>
                <span>
                  <?= htmlspecialchars($item['file_name']) ?> (number : <?= htmlspecialchars($item['number_file']) ?>)
                </span>
                <br>
                <small><?= htmlspecialchars($item['file_path']) ?></small>
              </div>
              <button class="btn-delete" onclick="deleteMathFlashcard(<?= $item['id'] ?>)">
                🗑 Delete
              </button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

  </div>

  <script>
    // change admin email and password
    function updateAdmin() {
      const newEmail = document.getElementById('newEmail').value;
      const currentPassword = document.getElementById('currentPassword').value;
      const newPassword = document.getElementById('newPassword').value;

      fetch('update_admin.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            newEmail,
            currentPassword,
            newPassword
          })
        })
        .then(response => response.json())
        .then(data => {
          const msgEl = document.getElementById('changeMsg');
          if (data.success) {
            msgEl.textContent = 'Admin updated successfully!';
            msgEl.style.color = 'green';

            setTimeout(() => {
              location.reload();
            }, 2000);

          } else {
            msgEl.textContent = 'Error: ' + (data.error || 'Unknown error');
            msgEl.style.color = 'red';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          const msgEl = document.getElementById('changeMsg');
          msgEl.textContent = 'Failed to update.';
          msgEl.style.color = 'red';
        });
    }

    // Helper function لتحديث اسم الملف
    function updateFileNameCustom(inputId, textSpanId) {
      const input = document.getElementById(inputId);
      const label = document.getElementById(textSpanId);
      if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        label.classList.add('selected');
      } else {
        // تحديد النص الافتراضي حسب نوع الحقل
        if (inputId.includes('Presentation') || inputId.includes('fp')) {
          label.textContent = 'Choose PPTX file...';
        } else {
          label.textContent = 'Choose PDF file...';
        }
        label.classList.remove('selected');
      }
    }

    function updateFileName(input) {
      const label = document.getElementById('fileText');
      if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        label.classList.add('selected');
      } else {
        label.textContent = 'Choose PDF file...';
        label.classList.remove('selected');
      }
    }

    function go() {
      window.location.href = "index.php";
    }

    function toggleMenu() {
      document.getElementById('sidebar').classList.toggle('open');
    }

    function show(id) {
      document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      document.getElementById('sidebar').classList.remove('open');

      // حفظ القسم الحالي
      localStorage.setItem('activeSection', id);
    }

    // ========== FUNCTIONS ORIGINAL (games, math games, footer, files) ==========
    function saveGame() {
      const gLink = document.getElementById('gLink').value;
      const gName = document.getElementById('gName').value;
      fetch('save_game_settings.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            gLink,
            gName
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Game updated successfully!');
            location.reload();
          } else {
            alert('Error: ' + (data.error || 'Unknown error'));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to save.');
        });
    }

    function saveMathGame() {
      const mLink = document.getElementById('mLink').value;
      const mName = document.getElementById('mName').value;
      fetch('save_math_settings.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            mLink,
            mName
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Math game updated successfully!');
            location.reload();
          } else {
            alert('Error: ' + (data.error || 'Unknown error'));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to save.');
        });
    }

    function saveFooter() {
      const insta = document.getElementById('insta').value;
      const email = document.getElementById('email').value;
      const phone = document.getElementById('phone').value;
      fetch('save_footer_settings.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            instagram: insta,
            email,
            phone
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) alert('Footer updated successfully!');
          else alert('Error: ' + (data.error || 'Unknown error'));
        })
        .catch(error => console.error('Error:', error));
    }

    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('btn-delete') && (e.target.dataset.type === 'english' || e.target.dataset.type === 'math')) {
        const id = e.target.dataset.id;
        const type = e.target.dataset.type;
        if (!confirm('Are you sure you want to delete this game?')) return;
        let url = (type === 'english') ? 'delete_english_game.php' : 'delete_math_game.php';
        fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              id
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) e.target.closest('.list-item').remove();
            else alert('Delete failed');
          })
          .catch(err => console.error(err));
      }
    });

    function savefile_english_worksheet() {
      const title = document.getElementById('fileTitle').value;
      const file = document.getElementById('fileInput').files[0];
      if (!title || !file) {
        alert("Please fill all fields");
        return;
      }
      if (file.type !== 'application/pdf') {
        alert("Only PDF files are allowed.");
        return;
      }
      let formData = new FormData();
      formData.append("title", title);
      formData.append("file", file);
      fetch("upload_file_english_worksheet.php", {
          method: "POST",
          body: formData
        })
        .then(res => res.text())
        .then(data => {
          alert(data);
          location.reload();
        })
        .catch(err => console.log(err));
    }

    function deleteFile_english_worksheet(id) {
      if (!confirm("Are you sure you want to delete this file?")) return;
      fetch("delete_file_english_worksheet.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            id
          })
        })
        .then(res => res.text())
        .then(data => {
          alert(data);
          location.reload();
        });
    }

    // ========== دوال الأقسام الجديدة ==========
    // Math Worksheets (PDF)
    function addMathWorksheet() {
      const title = document.getElementById('mathWsTitle').value;
      const file = document.getElementById('mathWsFile').files[0];
      if (!title || !file) {
        alert("Please enter title and select PDF file");
        return;
      }
      if (file.type !== 'application/pdf') {
        alert("Only PDF files are allowed for worksheets.");
        return;
      }
      let formData = new FormData();
      formData.append('title', title);
      formData.append('file', file);
      formData.append('type', 'math_worksheet');
      fetch('upload_file_math_worksheet.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert(data.error || 'Upload failed');
        });
    }

    function deleteMathWorksheet(id) {
      if (!confirm("Delete this math worksheet?")) return;
      fetch('delete_file_math_worksheet.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id,
            type: 'math_worksheet'
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert('Delete failed');
        });
    }

    // Sight Words Worksheets (PDF)
    function addSightWordsWorksheet() {
      const title = document.getElementById('sightWsTitle').value;
      const file = document.getElementById('sightWsFile').files[0];
      if (!title || !file) {
        alert("Please enter title and select PDF file");
        return;
      }
      if (file.type !== 'application/pdf') {
        alert("Only PDF files are allowed for worksheets.");
        return;
      }
      let formData = new FormData();
      formData.append('title', title);
      formData.append('file', file);
      formData.append('type', 'sight_words_worksheet');
      fetch('upload_file_sight_worksheet.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert(data.error || 'Upload failed');
        });
    }

    function deleteSightWordsWorksheet(id) {
      if (!confirm("Delete this sight words worksheet?")) return;
      fetch('delete_file_sight_worksheet.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id,
            type: 'sight_words_worksheet'
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert('Delete failed');
        });
    }

    // Word Families Worksheets (PDF)
    function addWordFamiliesWorksheet() {
      const title = document.getElementById('wordFamiliesWsTitle').value;
      const file = document.getElementById('wordFamiliesWsFile').files[0];
      if (!title || !file) {
        alert("Please enter title and select PDF file");
        return;
      }
      if (file.type !== 'application/pdf') {
        alert("Only PDF files are allowed for worksheets.");
        return;
      }
      let formData = new FormData();
      formData.append('title', title);
      formData.append('file', file);
      formData.append('type', 'word_families_worksheet');
      fetch('upload_file_word_worksheet.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert(data.error || 'Upload failed');
        });
    }

    function deleteWordFamiliesWorksheet(id) {
      if (!confirm("Delete this word families worksheet?")) return;
      fetch('delete_file_word_worksheet.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id,
            type: 'word_families_worksheet'
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert('Delete failed');
        });
    }

    // Fun Presentations (رفع ملف PPTX)
    function addFunPresentation(category) {
      let titleInput, fileInput;
      if (category === 'word_families') {
        titleInput = document.getElementById('fpWordFamiliesTitle');
        fileInput = document.getElementById('fpWordFamiliesFile');
      } else if (category === 'english') {
        titleInput = document.getElementById('fpEnglishTitle');
        fileInput = document.getElementById('fpEnglishFile');
      } else if (category === 'sight_words') {
        titleInput = document.getElementById('fpSightWordsTitle');
        fileInput = document.getElementById('fpSightWordsFile');
      } else if (category === 'math') {
        titleInput = document.getElementById('fpMathTitle');
        fileInput = document.getElementById('fpMathFile');
      } else return;

      const title = titleInput.value;
      const file = fileInput.files[0];
      if (!title || !file) {
        alert("Please enter title and select PPTX file");
        return;
      }
      if (!file.name.endsWith('.pptx')) {
        alert("Only .pptx files are allowed for presentations.");
        return;
      }

      let formData = new FormData();
      formData.append('title', title);
      formData.append('file', file);
      formData.append('category', category);

      fetch('upload_file_presentation.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert(data.error || 'Upload failed');
        });
    }

    function deleteFunPresentation(category, id) {
      if (!confirm("Delete this presentation?")) return;
      fetch('delete_file_presentation.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            category,
            id
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert('Delete failed');
        });
    }

    // ========== دوال Flashcards الجديدة ==========
    // English Flashcards
    function addEnglishFlashcard() {
      const title = document.getElementById('englishFcTitle').value;
      const fileInput = document.getElementById('englishFcFile');
      const letter = document.getElementById('englishFcLetter').value;

      if (!title || !fileInput.files.length || !letter) {
        alert('Please fill all fields');
        return;
      }

      const formData = new FormData();
      formData.append('title', title);
      formData.append('file', fileInput.files[0]);
      formData.append('letter', letter);

      fetch('add_english_flashcard.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert('Upload failed');
        });
    }

    function deleteEnglishFlashcard(id) {
      if (!confirm("Delete this file?")) return;

      fetch('delete_english_flashcard.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert('Delete failed');
        });
    }

    // Math Flashcards
    function addMathFlashcard() {
      const title = document.getElementById('mathFcTitle').value;
      const file = document.getElementById('mathFcFile').files[0];
      const number = document.getElementById('mathFcNumber').value;
      if (!title || !file || !number) {
        alert("Please enter all fields");
        return;
      }
      if (file.type !== 'application/pdf') {
        alert("Only PDF files are allowed for flashcards.");
        return;
      }
      let formData = new FormData();
      formData.append('title', title);
      formData.append('file', file);
      formData.append('number', number);
      formData.append('type', 'math_flashcard');
      fetch('add_math_flashcard.php', {
          method: 'POST',
          body: formData
        })
        .then(async res => {
          const text = await res.text();
          console.log(text); // 👈 مهم جدًا
          return JSON.parse(text);
        })
        .then(data => {
          if (data.success) location.reload();
          else alert(data.error || 'Upload failed');
        })
        .catch(err => {
          console.log("Error:", err);
        });
    }

    function deleteMathFlashcard(id) {
      if (!confirm("Delete this math flashcard?")) return;
      fetch('delete_math_flashcard.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id,
            type: 'math_flashcard'
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) location.reload();
          else alert('Delete failed');
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
      const saved = localStorage.getItem('activeSection');

      if (saved && document.getElementById(saved)) {
        document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
        document.getElementById(saved).classList.add('active');
      }
    });
  </script>
</body>

</html>
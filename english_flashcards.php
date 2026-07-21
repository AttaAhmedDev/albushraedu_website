<?php
session_start();
require_once 'db.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Magic Letters Kids Pro</title>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box
    }

    body {
      font-family: 'Fredoka', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #4facfe, #43e97b);
    }

    /* floating icons */
    .icon {
      position: absolute;
      font-size: 28px;
      opacity: .8;
      animation: floatIcon linear infinite;
    }

    @keyframes floatIcon {
      0% {
        transform: translateY(100vh) rotate(0deg)
      }

      100% {
        transform: translateY(-10vh) rotate(360deg)
      }
    }

    .container {
      padding: 35px 25px;
      border-radius: 35px;
      width: 95%;
      max-width: 1100px;
      text-align: center;
      box-shadow: 0 25px 60px rgba(0, 0, 0, .25);
      position: relative;
      z-index: 2;
    }

    h1 {
      font-size: 2.7rem;
      margin-bottom: 15px;
      color: #ff4d4d;
    }

    .grid {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .row {
      display: flex;
      justify-content: center;
      gap: 18px;
      flex-wrap: wrap;
    }

    .letter {
      width: 95px;
      height: 95px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.6rem;
      color: white;
      cursor: pointer;
      position: relative;
      transition: .3s;
      box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
      animation: float 3s ease-in-out infinite;
    }

    /* colors */
    .letter:nth-child(3n+1) {
      background: linear-gradient(145deg, #4facfe, #00c6ff)
    }

    .letter:nth-child(3n+2) {
      background: linear-gradient(145deg, #43e97b, #38f9d7)
    }

    .letter:nth-child(3n+3) {
      background: linear-gradient(145deg, #ff4d4d, #ff6a6a)
    }

    .letter:hover {
      transform: translateY(-12px) scale(1.12);
    }

    /* string */
    .letter::after {
      content: "";
      position: absolute;
      bottom: -28px;
      width: 2px;
      height: 28px;
      background: #444;
    }

    @keyframes float {
      0% {
        transform: translateY(0)
      }

      50% {
        transform: translateY(-8px)
      }

      100% {
        transform: translateY(0)
      }
    }

    @media(max-width:600px) {
      .letter {
        width: 70px;
        height: 70px;
        font-size: 2rem
      }

      h1 {
        font-size: 2rem
      }
    }
  </style>
</head>

<body>
  <script>
    const icons = ['🎈', '🧸', '🪁', '⭐', '🎮', '🚗', '🧩', '🎨', '🐣'];
    for (let i = 0; i < 20; i++) {
      const el = document.createElement('div');
      el.className = 'icon';
      el.textContent = icons[Math.floor(Math.random() * icons.length)];
      el.style.left = Math.random() * 100 + 'vw';
      el.style.animationDuration = (5 + Math.random() * 5) + 's';
      el.style.fontSize = (20 + Math.random() * 30) + 'px';
      document.body.appendChild(el);
    }
  </script>
  <div class="container">
    <h1>🎈 Magic Letters 🎈</h1>
    <div class="grid" id="grid"></div>
  </div>

  <script>
    const rows = [
      ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
      ['H', 'I', 'J', 'K', 'L', 'M', 'N'],
      ['O', 'P', 'Q', 'R', 'S', 'T', 'U'],
      ['V', 'W', 'X', 'Y', 'Z']
    ];
    const grid = document.getElementById('grid');
    rows.forEach(r => {
      const row = document.createElement('div');
      row.className = 'row';

      r.forEach(l => {
        const div = document.createElement('div');
        div.className = 'letter';
        div.textContent = l;

        div.onclick = () => {
          div.style.transform = 'scale(0.9)';
          setTimeout(() => div.style.transform = '', 150);

          window.location.href = 'download_letter.php?letter=' + l;
        }
        row.appendChild(div);
      })
      grid.appendChild(row);
    })
  </script>

</body>

</html>
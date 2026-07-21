<?php
session_start();
require_once 'db.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Magic Numbers Kids</title>

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
      opacity: .85;
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
      flex-wrap: wrap;
      justify-content: center;
      gap: 18px;
    }

    .number {
      width: 90px;
      height: 90px;
      border-radius: 25px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: white;
      cursor: pointer;
      position: relative;
      transition: .3s;
      box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
      animation: bounce 2.5s infinite ease-in-out;
    }

    /* colors */
    .number:nth-child(4n+1) {
      background: linear-gradient(145deg, #4facfe, #00c6ff)
    }

    .number:nth-child(4n+2) {
      background: linear-gradient(145deg, #43e97b, #38f9d7)
    }

    .number:nth-child(4n+3) {
      background: linear-gradient(145deg, #ff4d4d, #ff6a6a)
    }

    .number:nth-child(4n+4) {
      background: linear-gradient(145deg, #f7971e, #ffd200)
    }

    .number:hover {
      transform: scale(1.15) rotate(5deg);
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateY(0)
      }

      50% {
        transform: translateY(-10px)
      }
    }

    @media(max-width:600px) {
      .number {
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
    const icons = ['🎈', '🧸', '🪁', '⭐', '🎮', '🚗', '🧩', '🎨', '🐣', '🍭', '⚽'];
    for (let i = 0; i < 25; i++) {
      const el = document.createElement('div');
      el.className = 'icon';
      el.textContent = icons[Math.floor(Math.random() * icons.length)];
      el.style.left = Math.random() * 100 + 'vw';
      el.style.animationDuration = (5 + Math.random() * 6) + 's';
      el.style.fontSize = (20 + Math.random() * 30) + 'px';
      document.body.appendChild(el);
    }
  </script>

  <div class="container">
    <h1> Magic Numbers 1 - 20 </h1>
    <div class="grid" id="grid"></div>
  </div>

  <script>
    const grid = document.getElementById('grid');

    function createRow() {
      const row = document.createElement('div');
      row.style.display = 'flex';
      row.style.gap = '18px';
      row.style.justifyContent = 'center';
      row.style.width = '100%';
      return row;
    }

    let row = createRow();

    for (let i = 1; i <= 20; i++) {
      const div = document.createElement('div');
      div.className = 'number';
      div.textContent = i;

      div.onclick = () => {
        div.style.transform = 'scale(0.9)';
        setTimeout(() => div.style.transform = '', 150);

        window.location.href = 'download_number.php?number=' + i;
      }

      row.appendChild(div);

      if (i % 5 === 0) {
        grid.appendChild(row);
        row = createRow();
      }
    }
  </script>

</body>

</html>
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
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <title>Albushra World | Playful Kindergarten Learning</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Quicksand:wght@300;400;500;600;700;800&family=Kalam:wght@300;400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* ========== جميع الأنماط السابقة كما هي (نفس الكود الذي أرسلته) ========== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: #f3e2d6;
      overflow-x: hidden;
      cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="27" viewBox="0 0 24 27"><polygon points="2,2 22,13 12,16 2,25" fill="%23FFB347" stroke="%23FF6B6B" stroke-width="1.5"/><circle cx="12" cy="13" r="3" fill="white"/></svg>') 12 13, auto;
    }

    .floating-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
    }

    .floating-bg div {
      position: absolute;
      background: rgba(255, 200, 150, 0.2);
      border-radius: 50%;
      animation: floatAround 20s infinite linear;
    }

    @keyframes floatAround {
      0% {
        transform: translateY(0) rotate(0deg);
        opacity: 0.3;
      }

      50% {
        transform: translateY(-40px) rotate(180deg);
        opacity: 0.6;
      }

      100% {
        transform: translateY(0) rotate(360deg);
        opacity: 0.3;
      }
    }

    .fade-up {
      opacity: 0;
      transform: translateY(35px);
      transition: opacity 0.8s cubic-bezier(0.34, 1.3, 0.64, 1), transform 0.8s cubic-bezier(0.34, 1.3, 0.64, 1);
    }

    .fade-up.appear {
      opacity: 1;
      transform: translateY(0);
    }

    .scale-on-scroll {
      opacity: 0;
      transform: scale(0.9) rotate(-1deg);
      transition: opacity 0.5s cubic-bezier(0.34, 1.4, 0.64, 1), transform 0.5s cubic-bezier(0.34, 1.4, 0.64, 1);
    }

    .scale-on-scroll.appear {
      opacity: 1;
      transform: scale(1) rotate(0deg);
    }

    .delay-1 {
      transition-delay: 0.05s;
    }

    .delay-2 {
      transition-delay: 0.1s;
    }

    .delay-3 {
      transition-delay: 0.15s;
    }

    .delay-4 {
      transition-delay: 0.2s;
    }

    .delay-5 {
      transition-delay: 0.25s;
    }

    .delay-6 {
      transition-delay: 0.3s;
    }

    .delay-7 {
      transition-delay: 0.35s;
    }

    .delay-8 {
      transition-delay: 0.4s;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background: #fef9f4;
      flex-wrap: wrap;
      gap: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: all 0.2s ease;
    }

    .logo {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .star {
      font-size: 32px;
      animation: starSpin 3s infinite alternate;
    }

    @keyframes starSpin {
      0% {
        transform: rotate(0deg) scale(1);
      }

      100% {
        transform: rotate(15deg) scale(1.1);
      }
    }

    .logo h3 {
      color: #e63946;
      font-weight: 700;
      font-size: 1.2rem;
    }

    .logo p {
      font-size: 11px;
      color: #888;
      font-weight: 500;
    }

    .hamburger {
      display: none;
      flex-direction: column;
      cursor: pointer;
      gap: 5px;
      background: none;
      border: none;
      padding: 8px;
      z-index: 200;
    }

    .hamburger span {
      width: 28px;
      height: 3px;
      background: #e63946;
      border-radius: 4px;
      transition: all 0.25s ease-in-out;
    }

    .hamburger.active span:nth-child(1) {
      transform: rotate(45deg) translate(5px, 5px);
    }

    .hamburger.active span:nth-child(2) {
      opacity: 0;
      transform: scale(0.8);
    }

    .hamburger.active span:nth-child(3) {
      transform: rotate(-45deg) translate(7px, -6px);
    }

    nav {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      transition: all 0.3s ease;
    }

    nav a {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-left: 6px;
      text-decoration: none;
      color: #2c3e4e;
      font-weight: 550;
      font-size: 12px;
      cursor: pointer;
      transition: all 0.25s ease-in-out;
      padding: 6px 0px;
      border-radius: 40px;
    }

    nav a .nav-icon {
      font-size: 1.2rem;
    }

    nav a:hover {
      text-decoration: underline;
      text-underline-offset: 8px;
      text-decoration-thickness: 2.5px;
      transform: translateY(-2px);
    }

    nav a:not(.active):hover {
      color: #ff4d4d;
      text-decoration-color: #ff9f4a;
    }

    nav .active {
      background: #ff4d4d;
      color: white;
      padding: 6px 18px;
      margin-left: 0;
      border-radius: 40px;
    }

    @media (max-width: 768px) {
      .navbar {
        padding: 12px 20px;
      }

      .hamburger {
        display: flex;
      }

      nav {
        position: absolute;
        top: 75px;
        left: 0;
        width: 100%;
        background: #fef9f4;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        padding: 25px 20px;
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
        border-radius: 0 0 24px 24px;
        transform: translateY(-120%);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        z-index: 99;
      }

      nav.open {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
      }

      nav a {
        margin-left: 0;
        padding: 8px 20px;
        width: 80%;
        justify-content: center;
      }

      nav .active {
        background: #ff4d4d;
        width: auto;
        min-width: 160px;
        justify-content: center;
      }
    }

    .letters {
      background: #0a1622;
      background-image: radial-gradient(circle at 10% 30%, rgba(255, 215, 150, 0.2) 2%, transparent 2.5%);
      background-size: 28px 28px;
      padding: 12px 20px;
      border-bottom: 3px solid #ff4d4d;
      overflow-x: auto;
      white-space: nowrap;
      scrollbar-width: none;
      position: relative;
      z-index: 2;
    }

    .letters::-webkit-scrollbar {
      display: none;
    }

    .letters-wrapper {
      display: inline-flex;
      flex-wrap: nowrap;
      gap: 2.5rem;
      align-items: baseline;
      justify-content: flex-start;
      padding: 0 20px;
    }

    .letters span {
      display: inline-block;
      font-size: 1rem;
      font-weight: 800;
      text-shadow: 0 2px 3px rgba(0, 0, 0, 0.3);
      animation: bounceLetter 1.2s infinite ease-in-out;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .letters span:hover {
      transform: scale(1.3);
    }

    @keyframes bounceLetter {

      0%,
      100% {
        transform: translateY(0px);
      }

      50% {
        transform: translateY(-5px);
      }
    }

    .letters span:nth-child(1) {
      color: #FF5E7C;
    }

    .letters span:nth-child(2) {
      color: #FFB347;
    }

    .letters span:nth-child(3) {
      color: #F4D03F;
    }

    .letters span:nth-child(4) {
      color: #2ECC71;
    }

    .letters span:nth-child(5) {
      color: #1ABC9C;
    }

    .letters span:nth-child(6) {
      color: #3498DB;
    }

    .letters span:nth-child(7) {
      color: #9B59B6;
    }

    .letters span:nth-child(8) {
      color: #E67E22;
    }

    .letters span:nth-child(9) {
      color: #E84393;
    }

    .letters span:nth-child(10) {
      color: #00CEC9;
    }

    .letters span:nth-child(11) {
      color: #FD7272;
    }

    .letters span:nth-child(12) {
      color: #F8C291;
    }

    .letters span:nth-child(13) {
      color: #6A89CC;
    }

    .letters span:nth-child(14) {
      color: #82CCDD;
    }

    .letters span:nth-child(15) {
      color: #B8E994;
    }

    .letters span:nth-child(16) {
      color: #F3A683;
    }

    .letters span:nth-child(17) {
      color: #F5CD79;
    }

    .letters span:nth-child(18) {
      color: #EA868F;
    }

    .letters span:nth-child(19) {
      color: #A3CB38;
    }

    .letters span:nth-child(20) {
      color: #FF9F4A;
    }

    .letters span:nth-child(21) {
      color: #CD84F1;
    }

    .letters span:nth-child(22) {
      color: #FF4D4D;
    }

    .letters span:nth-child(23) {
      color: #32FF7E;
    }

    .letters span:nth-child(24) {
      color: #7EFFDB;
    }

    .letters span:nth-child(25) {
      color: #FFB8B8;
    }

    .letters span:nth-child(26) {
      color: #D980FA;
    }

    .hero {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 70px 80px;
      flex-wrap: wrap;
      gap: 50px;
      z-index: 2;
    }

    .circle {
      position: absolute;
      border-radius: 50%;
      opacity: 0.35;
      z-index: 0;
      animation: pulse 6s infinite alternate;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 0.3;
      }

      100% {
        transform: scale(1.1);
        opacity: 0.5;
      }
    }

    .c1 {
      width: 180px;
      height: 180px;
      background: #f7b7a3;
      top: 70px;
      left: 30px;
    }

    .c2 {
      width: 140px;
      height: 140px;
      background: #a0c4ff;
      right: 70px;
      top: 180px;
    }

    .c3 {
      width: 200px;
      height: 200px;
      background: #90dbb5;
      bottom: 50px;
      left: 15%;
    }

    .hero-left {
      position: relative;
      z-index: 3;
    }

    .img-box {
      border: 6px solid #ff4d4d;
      border-radius: 32px;
      padding: 10px;
      background: #fffaf5;
      width: 280px;
      box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s;
    }

    .img-box:hover {
      transform: scale(1.02);
    }

    .img-box img {
      width: 100%;
      border-radius: 24px;
      display: block;
      object-fit: cover;
    }

    .badge {
      position: absolute;
      bottom: -12px;
      right: -12px;
      background: #ffd60a;
      width: 55px;
      height: 55px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      border: 4px solid white;
      animation: wiggle 2s infinite;
    }

    @keyframes wiggle {

      0%,
      100% {
        transform: rotate(0deg);
      }

      25% {
        transform: rotate(10deg);
      }

      75% {
        transform: rotate(-10deg);
      }
    }

    .hero-right {
      max-width: 620px;
      z-index: 3;
    }

    .hero-right h5 {
      color: #ff4d4d;
      letter-spacing: 3px;
      font-weight: 600;
      margin-bottom: 12px;
    }

    .hero-right h1 {
      font-size: 58px;
      color: #8e1c5c;
      line-height: 1.2;
      font-weight: 800;
    }

    .hero-right h1 span {
      background: linear-gradient(135deg, #ff4d4d, #ff9f4a);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
    }

    .hero-right p {
      margin: 24px 0;
      color: #2c3e4e;
      line-height: 1.65;
      font-weight: 500;
      font-size: 1.25rem;
    }

    .tags {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 20px;
    }

    .tags span {
      display: inline-block;
      padding: 8px 20px;
      background: #fff2e6;
      border: 1.5px solid #ffb5a7;
      border-radius: 60px;
      font-size: 14px;
      font-weight: 600;
      color: #bc3900;
      transition: all 0.2s;
    }

    .tags span:hover {
      transform: translateY(-3px);
      background: #ffe6d5;
    }

    .teacher-section {
      padding: 60px 20px;
      position: relative;
      overflow: hidden;
      background: #fef6f0;
      margin-top: 0;
      z-index: 2;
      scroll-margin-top: 100px;
    }

    .teacher-container {
      max-width: 1100px;
      margin: 0 auto;
      position: relative;
      z-index: 2;
    }

    .bg-circle {
      position: absolute;
      border-radius: 50%;
      z-index: 0;
      pointer-events: none;
    }

    .circle-1 {
      width: 220px;
      height: 220px;
      top: -60px;
      left: -80px;
      background: rgba(255, 159, 74, 0.15);
      animation: floatSlow 12s infinite alternate;
    }

    .circle-2 {
      width: 280px;
      height: 280px;
      bottom: -100px;
      right: -70px;
      background: rgba(255, 77, 77, 0.1);
      animation: floatSlow 15s infinite alternate-reverse;
    }

    @keyframes floatSlow {
      0% {
        transform: translate(0, 0);
      }

      100% {
        transform: translate(20px, 20px);
      }
    }

    .teacher-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .subtitle {
      display: inline-block;
      background: #ffe2d4;
      padding: 6px 18px;
      border-radius: 40px;
      font-weight: 600;
      font-size: 0.85rem;
      letter-spacing: 1px;
      color: #e63946;
      margin-bottom: 14px;
    }

    .teacher-header h2 {
      font-size: 1.7rem;
      font-weight: 700;
      color: #2c3e4e;
      max-width: 750px;
      margin: 0 auto;
      line-height: 1.3;
    }

    .teacher-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 22px;
      margin: 25px 0 35px;
    }

    .teacher-card {
      background: #fffefc;
      border-radius: 28px;
      padding: 18px 16px;
      box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.06);
      transition: all 0.3s ease;
      border: 2px solid #ffded5;
      text-align: center;
    }

    .teacher-card:hover {
      transform: translateY(-8px) rotate(0.5deg);
      box-shadow: 0 20px 30px -12px rgba(230, 57, 70, 0.2);
      border-color: #ffb5a7;
    }

    .card-icon {
      font-size: 2.5rem;
      margin-bottom: 12px;
      display: inline-block;
      animation: bounceSoft 2s infinite;
    }

    @keyframes bounceSoft {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-5px);
      }
    }

    .teacher-card h3 {
      font-size: 1.25rem;
      font-weight: 700;
      color: #e63946;
      margin-bottom: 10px;
    }

    .teacher-card p {
      color: #3a5468;
      font-weight: 400;
      line-height: 1.5;
      font-size: 0.8rem;
    }

    .teacher-footer {
      text-align: center;
      margin-top: 25px;
      padding-top: 15px;
      border-top: 2px dashed #ffccbc;
    }

    .teacher-footer span {
      background: #fff0e6;
      padding: 6px 20px;
      border-radius: 60px;
      font-weight: 500;
      color: #bc3900;
      font-size: 0.85rem;
      display: inline-block;
    }

    .learning-session {
      max-width: 1100px;
      margin: 0 auto;
      padding: 60px 24px 80px 24px;
      background: transparent;
      position: relative;
      z-index: 2;
    }

    .session-title {
      text-align: center;
      font-size: 2.8rem;
      font-weight: 800;
      color: #1F2F3C;
      margin-bottom: 0.75rem;
      letter-spacing: -0.3px;
      text-shadow: 3px 3px 0 rgba(255, 200, 150, 0.5);
    }

    .session-description {
      text-align: center;
      font-size: 1.2rem;
      color: #3a4e62;
      background: transparent;
      max-width: 650px;
      margin: 0 auto 2.5rem auto;
      line-height: 1.4;
      font-weight: 500;
    }

    .cards-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .learning-card {
      border-radius: 28px;
      padding: 1.2rem 0.5rem;
      text-align: center;
      box-shadow: 0 12px 20px -12px rgba(0, 0, 0, 0.15);
      transition: all 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.7rem;
      cursor: pointer;
      border: 3px solid;
    }

    .learning-card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 25px 30px -12px rgba(0, 0, 0, 0.25);
    }

    .learning-card:hover .card-icon-lg {
      animation: iconPop 0.4s ease;
    }

    @keyframes iconPop {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.3);
      }

      100% {
        transform: scale(1);
      }
    }

    .card-icon-lg {
      font-size: 2.8rem;
      display: inline-block;
      transition: transform 0.2s;
    }

    .card-title {
      font-size: 1rem;
      font-weight: 700;
      color: #2C3E50;
      letter-spacing: -0.2px;
    }

    .learning-card:nth-child(1) {
      background: #FFEAD2;
      border-color: #F5D0A6;
    }

    .learning-card:nth-child(2) {
      background: #D4F1F9;
      border-color: #B8D8E6;
    }

    .learning-card:nth-child(3) {
      background: #E0F7E8;
      border-color: #C5E8D4;
    }

    .learning-card:nth-child(4) {
      background: #FDE2C4;
      border-color: #F5CEA0;
    }

    .learning-card:nth-child(5) {
      background: #E9D8FD;
      border-color: #D4BEF0;
    }

    .learning-card:nth-child(6) {
      background: #FFDDD6;
      border-color: #FFC2B8;
    }

    .learning-card:nth-child(7) {
      background: #C9E4DE;
      border-color: #AECDC5;
    }

    .learning-card:nth-child(8) {
      background: #FCE1E4;
      border-color: #F8C5CA;
    }

    .extra-learning-wrapper {
      max-width: 1200px;
      margin: 20px auto 70px auto;
      padding: 0 24px;
    }

    .extra-title {
      text-align: center;
      font-size: 2.2rem;
      font-weight: 800;
      color: #e63946;
      background: rgba(255, 245, 235, 0.8);
      display: inline-block;
      padding: 8px 28px;
      border-radius: 60px;
      margin-bottom: 30px;
      backdrop-filter: blur(2px);
      letter-spacing: -0.3px;
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.05);
    }

    .kids-section {
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .card {
      width: 280px;
      padding: 28px 20px;
      border-radius: 32px;
      color: #fff;
      text-align: center;
      position: relative;
      overflow: hidden;
      transition: 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
      animation: floatCard 3s ease-in-out infinite;
      backdrop-filter: blur(1px);
      cursor: pointer;
    }

    .card::before {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.22), transparent 70%);
      animation: glowMove 5s linear infinite;
      pointer-events: none;
    }

    @keyframes glowMove {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    @keyframes floatCard {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-8px);
      }
    }

    .card:hover {
      transform: translateY(-12px) scale(1.03);
      box-shadow: 0 28px 40px rgba(0, 0, 0, 0.25);
    }

    .card .icon {
      font-size: 48px;
      margin-bottom: 12px;
      display: inline-block;
      filter: drop-shadow(2px 4px 6px rgba(0, 0, 0, 0.2));
    }

    .card h2 {
      margin: 12px 0 8px;
      font-size: 26px;
      font-weight: 800;
      letter-spacing: -0.3px;
    }

    .card p {
      font-size: 14px;
      opacity: 0.95;
      line-height: 1.5;
      font-weight: 500;
    }

    .card a {
      display: inline-block;
      margin-top: 18px;
      padding: 10px 24px;
      border-radius: 40px;
      text-decoration: none;
      font-weight: 700;
      color: #fff;
      background: rgba(255, 255, 255, 0.25);
      transition: all 0.3s ease;
      backdrop-filter: blur(2px);
      font-size: 0.9rem;
    }

    .card:hover a {
      transform: translateY(-3px);
      background: rgba(0, 0, 0, 0.2);
    }

    .coming-soon-text {
      margin-top: 12px;
      font-size: 0.7rem;
      font-weight: 500;
      background: rgba(255, 255, 200, 0.3);
      display: inline-block;
      padding: 4px 12px;
      border-radius: 40px;
      backdrop-filter: blur(2px);
      letter-spacing: 0.3px;
    }

    .card1 {
      background: linear-gradient(135deg, rgba(0, 200, 83, 0.88), rgba(70, 230, 130, 0.88));
      box-shadow: 0 15px 30px rgba(0, 200, 83, 0.3);
    }

    .card2 {
      background: linear-gradient(135deg, rgba(33, 150, 243, 0.88), rgba(80, 180, 255, 0.88));
      box-shadow: 0 15px 30px rgba(33, 150, 243, 0.3);
    }

    .card3 {
      background: linear-gradient(135deg, rgba(255, 77, 77, 0.88), rgba(255, 130, 100, 0.88));
      box-shadow: 0 15px 30px rgba(255, 77, 77, 0.3);
    }

    .teacher-message-section {
      max-width: 900px;
      margin: 20px auto 60px auto;
      padding: 0 20px;
      position: relative;
      z-index: 2;
    }

    .teacher-message-card {
      background: #fffaf2;
      border-radius: 48px;
      padding: 1.4rem 1.8rem;
      box-shadow: 0 25px 40px -15px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 240, 0.9);
      border: 1px solid #ffddb0;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
      animation: gentleRise 0.5s ease-out forwards;
    }

    .teacher-message-card::before {
      content: "🌸";
      font-size: 100px;
      position: absolute;
      bottom: -20px;
      right: -15px;
      opacity: 0.1;
      pointer-events: none;
      transform: rotate(-8deg);
    }

    .teacher-message-card::after {
      content: "📚";
      font-size: 80px;
      position: absolute;
      top: -15px;
      left: -20px;
      opacity: 0.07;
      pointer-events: none;
      transform: rotate(12deg);
    }

    @keyframes gentleRise {
      0% {
        opacity: 0;
        transform: translateY(12px) scale(0.98);
      }

      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .message-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1rem;
      border-bottom: 2px dotted #f7bc6d;
      padding-bottom: 0.5rem;
      flex-wrap: wrap;
    }

    .message-header i {
      font-size: 1.8rem;
      color: #f5a65b;
      background: #fff0e0;
      padding: 0.4rem;
      border-radius: 50px;
    }

    .message-header h2 {
      font-family: 'Kalam', cursive;
      font-size: 1.4rem;
      font-weight: 700;
      color: #c25d2e;
      letter-spacing: -0.2px;
      margin: 0;
    }

    .teacher-quote {
      font-size: 0.9rem;
      line-height: 1.45;
      color: #2c3e2f;
      margin: 0.8rem 0 0.8rem;
      background: rgba(255, 245, 225, 0.7);
      padding: 1rem 1.2rem;
      border-radius: 40px;
      font-weight: 500;
    }

    .teacher-quote p {
      margin-bottom: 0.7rem;
    }

    /* تنسيق الصورة الدائرية في التوقيع */
    .teacher-icon {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      object-fit: cover;
      display: inline-block;
      vertical-align: middle;
      margin-right: 6px;
    }

    .teacher-signature {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 0.5rem;
      font-family: 'Kalam', cursive;
      font-size: 1rem;
      font-weight: 600;
      color: #b85b1e;
      background: #fff4e8;
      padding: 0.5rem 1.2rem;
      border-radius: 40px;
      width: fit-content;
      margin-left: auto;
      border: 1px solid #ffe0b5;
    }

    .teacher-signature i {
      font-size: 1.1rem;
      margin-right: 4px;
    }

    .arabic-welcome {
      text-align: left;
      margin-top: 0.7rem;
      font-size: 0.7rem;
      opacity: 0.7;
      font-weight: 500;
      color: #a75f2c;
      display: flex;
      align-items: center;
      gap: 6px;
      background: #fff3e6;
      width: fit-content;
      padding: 0.2rem 1rem;
      border-radius: 40px;
    }

    .footer {
      background: #ec5c68;
      background-image: radial-gradient(circle at 10% 20%, rgba(255, 215, 150, 0.08) 2%, transparent 2.5%);
      background-size: 28px 28px;
      color: #fce8d9;
      padding: 2rem 1.5rem;
      text-align: center;
      border-top: 5px solid #ff9f4a;
      margin-top: 40px;
      position: relative;
      z-index: 2;
    }

    .footer-container {
      max-width: 1100px;
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
      background: rgba(255, 245, 220, 0.1);
      padding: 6px 18px;
      border-radius: 60px;
      backdrop-filter: blur(2px);
    }

    .footer-logo .star {
      font-size: 28px;
      animation: starSpin 3s infinite alternate;
    }

    .footer-logo h4 {
      font-family: 'Kalam', cursive;
      font-size: 1.3rem;
      font-weight: 600;
      color: #ffd966;
      margin: 0;
    }

    .footer-logo p {
      font-size: 0.7rem;
      opacity: 0.8;
      letter-spacing: 1px;
    }

    .footer-social {
      display: flex;
      gap: 28px;
      align-items: center;
      flex-wrap: wrap;
      justify-content: center;
    }

    .footer-social a {
      color: #ffddb0;
      font-size: 1.6rem;
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      background: rgba(255, 255, 200, 0.08);
      padding: 6px 14px;
      border-radius: 50px;
      font-size: 1rem;
      font-weight: 500;
    }

    .footer-social a i {
      font-size: 1.6rem;
    }

    .footer-social a:hover {
      color: #ffb347;
      transform: translateY(-4px);
      background: rgba(15, 139, 46, 0.2);
    }

    .footer-contact {
      display: flex;
      gap: 20px;
      font-size: 0.9rem;
      background: rgba(240, 189, 189, 0.2);
      padding: 8px 18px;
      border-radius: 60px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .footer-contact span {
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .footer-contact i {
      color: #ffb347;
    }

    .footer-copyright {
      margin-top: 1.8rem;
      font-size: 0.7rem;
      opacity: 0.7;
      border-top: 1px dashed #ffb587;
      padding-top: 1.2rem;
      width: 100%;
      text-align: center;
    }

    @media (max-width: 780px) {
      .footer-container {
        flex-direction: column;
        text-align: center;
      }

      .footer-social a {
        font-size: 0.9rem;
        padding: 4px 12px;
      }

      .footer-social a i {
        font-size: 1.3rem;
      }

      .cards-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
      }

      .session-title {
        font-size: 2.2rem;
      }

      .extra-title {
        font-size: 1.8rem;
      }

      .hero-right p {
        font-size: 1.1rem;
      }
    }

    @media (max-width: 500px) {
      .cards-row {
        grid-template-columns: 1fr;
        gap: 0.9rem;
      }

      .learning-card {
        flex-direction: row;
        justify-content: center;
        gap: 0.8rem;
        padding: 0.9rem;
      }

      .card-icon-lg {
        font-size: 2rem;
      }

      .kids-section {
        gap: 20px;
      }

      .card {
        width: 260px;
        padding: 22px 16px;
      }

      .hero-right p {
        font-size: 1rem;
      }
    }

    @media (max-width: 950px) {
      .hero {
        padding: 50px 30px;
        justify-content: center;
        text-align: center;
      }

      .hero-right {
        text-align: center;
      }

      .hero-right h1 {
        font-size: 46px;
      }

      .tags {
        justify-content: center;
      }
    }

    @media (max-width: 600px) {
      .hero-right h1 {
        font-size: 36px;
      }

      .letters span {
        font-size: 0.8rem;
      }

      .img-box {
        width: 240px;
      }

      .teacher-message-card {
        padding: 1rem 1.2rem;
        border-radius: 36px;
      }

      .teacher-quote {
        padding: 0.8rem;
        font-size: 0.85rem;
      }

      .message-header h2 {
        font-size: 1.2rem;
      }
    }

    .game-modal,
    .worksheets-modal,
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









    .teacher-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      object-position: top;
      /* مهم */
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

    .presentations-modal .modal-container {
      border-color: #ff8c42;
      background: #fff8f0;
    }

    .presentations-modal h2 {
      color: #e67e22;
    }

    .presentations-modal .modal-option {
      background: #fbefdf;
      border-color: #ffab6f;
      color: #b85a1c;
    }

    .presentations-modal .modal-option:hover {
      background: #ffe1b3;
      transform: translateY(-5px);
      box-shadow: 0 12px 20px -8px rgba(230, 126, 34, 0.3);
    }
  </style>
</head>

<body>
  <div class="floating-bg">
    <div style="width: 80px; height: 80px; top: 15%; left: 5%; animation-duration: 25s;"></div>
    <div style="width: 120px; height: 120px; bottom: 20%; right: 3%; animation-duration: 30s; background: rgba(255,180,100,0.15);"></div>
    <div style="width: 50px; height: 50px; top: 60%; left: 85%; animation-duration: 18s;"></div>
    <div style="width: 100px; height: 100px; bottom: 10%; left: 10%; animation-duration: 22s; background: rgba(150,200,255,0.15);"></div>
  </div>

  <header class="navbar">
    <div class="logo">
      <span class="star">🌟</span>
      <div>
        <h3>Ms. Albushra's World</h3>
        <p>KINDERGARTEN CORNER</p>
      </div>
    </div>
    <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
    <nav id="navMenu">
      <a href="index.php" class="active">🏠 Home</a>
      <a href="#" class="worksheets-trigger">📄 Worksheets</a>
      <a href="#" class="presentations-trigger">🎭 Fun Presentations</a>
      <a href="#" id="aboutMeLink">About Me</a>
      <a href="#" id="onlineGamesMainLink">🎮 Online Games</a>
      <a href="english_flashcards.php">📘 English Flashcards</a>
      <a href="math_flashcards.php">🧮 Math Flashcards</a>
      <?php if ($isLoggedIn && $userRole === 'admin'): ?>
        <a href="admin_dashboard.php">🛠 Dashboard</a>
      <?php endif; ?>
      <?php if ($isLoggedIn): ?>
        <a href="logout.php">🚪 Logout</a>
      <?php else: ?>
        <a href="login.php">🔐 Login</a>
        <a href="register.php">📝 Register</a>
      <?php endif; ?>
    </nav>
  </header>

  <div class="letters">
    <div class="letters-wrapper">
      <span>A</span><span>B</span><span>C</span><span>D</span><span>E</span><span>F</span><span>G</span><span>H</span>
      <span>I</span><span>J</span><span>K</span><span>L</span><span>M</span><span>N</span><span>O</span><span>P</span>
      <span>Q</span><span>R</span><span>S</span><span>T</span><span>U</span><span>V</span><span>W</span><span>X</span>
      <span>Y</span><span>Z</span>
    </div>
  </div>

  <section class="hero fade-up">
    <div class="circle c1"></div>
    <div class="circle c2"></div>
    <div class="circle c3"></div>
    <div class="hero-left">
      <div class="img-box">
        <img src="images/Gemini_Generated_Image_fc5puofc5puofc5p.png" alt="Ms. Albushra" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x500/FDF2E9/E63946?text=🌸+Teacher+Albushra';">
        <div class="badge">🍎</div>
      </div>
    </div>
    <div class="hero-right">
      <h5>WELCOME TO</h5>
      <h1>Ms. Albushra's <br><span>World!</span></h1>
      <p>Hello, wonderful families! I'm Ms. Albushra Ayesh, a passionate Kindergarten teacher dedicated to nurturing every child's love of learning. I believe that the early years are the most important — and I'm here to make them magical, meaningful, and fun</p>
      <div class="tags"><span>B.A. English Literature 📘</span><span>Certified Trainer (TOT) 🏅</span><span>M.A. Educational Leadership 🎓</span></div>
    </div>
  </section>

  <section id="meetTeacherSection" class="teacher-section">
    <div class="teacher-container">
      <div class="bg-circle circle-1"></div>
      <div class="bg-circle circle-2"></div>
      <div class="teacher-header fade-up"><span class="subtitle">🌟 MEET YOUR TEACHER 👩‍🏫</span>
        <h2>Experienced, creative, and deeply committed to every child's growth.</h2>
      </div>
      <div class="teacher-grid">
        <div class="teacher-card scale-on-scroll delay-1">
          <div class="card-icon">📚</div>
          <h3>Highly Qualified</h3>
          <p>Ms. Albushra holds a Bachelor's degree in English Literature, a Master's degree in Educational Management and Leadership, and is a certified Trainer of Trainers (TOT).</p>
        </div>
        <div class="teacher-card scale-on-scroll delay-2">
          <div class="card-icon">🌍</div>
          <h3>Bilingual Learning</h3>
          <p>Working in a bilingual environment, Ms. Albushra supports children in developing strong skills in both English and Arabic.</p>
        </div>
        <div class="teacher-card scale-on-scroll delay-3">
          <div class="card-icon">💡</div>
          <h3>Creative Teaching</h3>
          <p>Through creative activities, worksheets, videos, and play-based learning, she makes every lesson an adventure.</p>
        </div>
      </div>
      <div class="teacher-footer fade-up"><span>🍎 Let's learn & grow together! 🧸</span></div>
    </div>
  </section>

  <section class="learning-session">
    <div class="fade-up">
      <h2 class="session-title">📚 What Do We Learn?</h2>
      <p class="session-description">✨ A rich, well-rounded curriculum designed for young, curious minds! ✨</p>
    </div>
    <div class="cards-row">
      <div class="learning-card scale-on-scroll delay-1"><span class="card-icon-lg">🔤</span><span class="card-title">Letters & Words</span></div>
      <div class="learning-card scale-on-scroll delay-2"><span class="card-icon-lg">🔢</span><span class="card-title">Numbers & Math</span></div>
      <div class="learning-card scale-on-scroll delay-3"><span class="card-icon-lg">🗣️</span><span class="card-title">Language Skills</span></div>
      <div class="learning-card scale-on-scroll delay-4"><span class="card-icon-lg">👁️</span><span class="card-title">Sight Words</span></div>
    </div>
    <div class="cards-row">
      <div class="learning-card scale-on-scroll delay-5"><span class="card-icon-lg">🧠</span><span class="card-title">Cognitive Skills</span></div>
      <div class="learning-card scale-on-scroll delay-6"><span class="card-icon-lg">📖</span><span class="card-title">Story Time</span></div>
      <div class="learning-card scale-on-scroll delay-7"><span class="card-icon-lg">👨‍👩‍👧‍👦</span><span class="card-title">Word families</span></div>
      <div class="learning-card scale-on-scroll delay-8"><span class="card-icon-lg">🎨</span><span class="card-title">Arts & Crafts</span></div>
    </div>
  </section>

  <div class="extra-learning-wrapper">
    <div style="text-align: center;" class="fade-up">
      <div class="extra-title">🌟 More Learning Adventures Await! 🌟</div>
    </div>
    <section class="kids-section">
      <div class="card card1 fade-up delay-1">
        <div class="icon">📄</div>
        <h2>Worksheets</h2>
        <p>Carefully designed worksheets to build language, literacy, and number skills step by step.</p>
        <a href="#" class="worksheets-trigger card-link">Let’s Go →</a>
        <div class="coming-soon-text">🔜 Coming soon</div>
      </div>
      <div class="card card3 fade-up delay-3">
        <div class="icon">🎮</div>
        <h2>Activities & Games</h2>
        <p>Hands-on creative activities and interactive games that bring learning to life!</p>
        <a href="#" id="activitiesGameLink" class="card-link">Let’s Go →</a>
        <div class="coming-soon-text">🔜 Coming soon</div>
      </div>
    </section>
  </div>

  <div class="teacher-message-section fade-up">
    <div class="teacher-message-card">
      <div class="message-header"><i class="fas fa-envelope-open-text"></i>
        <h2>💌 A Message from Ms. Albushra Ayesh</h2>
      </div>
      <div class="teacher-quote">
        <p>🌸 "Dear parents and families, welcome to our classroom corner! This website is a space I have created with love and care — a place where your children can explore worksheets, watch educational videos, and enjoy fun activities that support their learning journey at home and at school.</p>
        <p>📚 My goal is to nurture each child's language, social, and cognitive skills in a warm, bilingual environment where both English and Arabic are celebrated. I design every lesson with creativity and purpose, because I truly believe that when learning is joyful, it becomes lasting.</p>
        <p>🌟 Every child is unique, capable, and full of potential. Together, let's help them shine! 🌟"</p>
      </div>
      <div class="teacher-signature">
        <img src="./images/Gemini_Generated_Image_fc5puofc5puofc5p.png" alt="teacher" class="teacher-icon">
        Ms. Albushra Ayesh
        <span class="sunflower-icon">🌻</span>
        <span style="font-size: 0.7rem; font-family: 'Quicksand';">Kindergarten</span>
      </div>
    </div>
  </div>

  <!-- GAME SELECTION MODAL -->
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

  <!-- WORKSHEETS MODAL with 4 options -->
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

  <!-- NEW FUN PRESENTATIONS MODAL -->
  <div id="presentationsModal" class="presentations-modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
      <button class="modal-close" id="closePresentationsModalBtn">&times;</button>
      <h2>🎭 Fun Presentations</h2>
      <p>Choose a presentation to watch and learn!</p>
      <div class="modal-options">
        <button id="presentationsWordFamiliesBtn" class="modal-option"><i class="fas fa-font"></i> Word Families</button>
        <button id="presentationsSightWordsBtn" class="modal-option"><i class="fas fa-eye"></i> Sight Words</button>
        <button id="presentationsMathBtn" class="modal-option"><i class="fas fa-calculator"></i> Math</button>
        <button id="presentationsEnglishBtn" class="modal-option"><i class="fas fa-book"></i> English</button>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    (function() {
      const hamburger = document.getElementById('hamburgerBtn');
      const navMenu = document.getElementById('navMenu');

      function closeMobileMenu() {
        if (navMenu) navMenu.classList.remove('open');
        if (hamburger) hamburger.classList.remove('active');
      }

      function openMobileMenu() {
        if (navMenu) navMenu.classList.add('open');
        if (hamburger) hamburger.classList.add('active');
      }
      if (hamburger && navMenu) {
        hamburger.addEventListener('click', (e) => {
          e.stopPropagation();
          navMenu.classList.contains('open') ? closeMobileMenu() : openMobileMenu();
        });
      }
      const mobileLinks = document.querySelectorAll('#navMenu a');
      mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
          if (window.innerWidth <= 768) closeMobileMenu();
        });
      });
      window.addEventListener('resize', () => {
        if (window.innerWidth > 768 && navMenu && navMenu.classList.contains('open')) closeMobileMenu();
      });

      const fadeElements = document.querySelectorAll('.fade-up');
      const scaleElements = document.querySelectorAll('.scale-on-scroll');
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('appear');
            observer.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.15
      });
      fadeElements.forEach(el => observer.observe(el));
      scaleElements.forEach(el => observer.observe(el));
      setTimeout(() => {
        fadeElements.forEach(el => {
          if (el.getBoundingClientRect().top < window.innerHeight - 80) el.classList.add('appear');
        });
        scaleElements.forEach(el => {
          if (el.getBoundingClientRect().top < window.innerHeight - 80) el.classList.add('appear');
        });
      }, 200);

      const msgCard = document.querySelector('.teacher-message-card');
      if (msgCard) {
        msgCard.addEventListener('dblclick', function(e) {
          const flower = document.createElement('div');
          flower.innerHTML = '🌼✨';
          flower.style.position = 'fixed';
          flower.style.left = e.clientX + 'px';
          flower.style.top = e.clientY + 'px';
          flower.style.fontSize = '1.5rem';
          flower.style.pointerEvents = 'none';
          flower.style.zIndex = '999';
          flower.style.transform = 'translate(-50%, -50%)';
          flower.style.opacity = '0.8';
          flower.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
          document.body.appendChild(flower);
          setTimeout(() => {
            flower.style.opacity = '0';
            flower.style.transform = 'translate(-50%, -50%) scale(1.4)';
            setTimeout(() => flower.remove(), 400);
          }, 250);
        });
      }

      const aboutLink = document.getElementById('aboutMeLink');
      const teacherSection = document.getElementById('meetTeacherSection');
      if (aboutLink && teacherSection) {
        aboutLink.addEventListener('click', (e) => {
          e.preventDefault();
          const offset = 90;
          const elementPosition = teacherSection.getBoundingClientRect().top + window.scrollY;
          window.scrollTo({
            top: elementPosition - offset,
            behavior: 'smooth'
          });
          history.pushState(null, null, ' ');
        });
      }

      const gameModal = document.getElementById('gameModal');
      const closeGameModalBtn = document.getElementById('closeGameModalBtn');
      const gameModalOverlay = document.querySelector('#gameModal .modal-overlay');
      const englishBtn = document.getElementById('englishGamesModalBtn');
      const mathBtn = document.getElementById('mathGamesModalBtn');
      const onlineGamesTrigger = document.getElementById('onlineGamesMainLink');
      const activitiesGameLink = document.getElementById('activitiesGameLink');

      function openGameModal(event) {
        if (event) event.preventDefault();
        if (gameModal) gameModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }

      function closeGameModal() {
        if (gameModal) gameModal.style.display = 'none';
        document.body.style.overflow = '';
      }
      if (onlineGamesTrigger) onlineGamesTrigger.addEventListener('click', openGameModal);
      if (activitiesGameLink) activitiesGameLink.addEventListener('click', openGameModal);
      const anyOnlineGameLinks = document.querySelectorAll('a[href="Online Games.html"], a[href="./Online Games.html"]');
      anyOnlineGameLinks.forEach(link => link.addEventListener('click', openGameModal));
      if (closeGameModalBtn) closeGameModalBtn.addEventListener('click', closeGameModal);
      if (gameModalOverlay) gameModalOverlay.addEventListener('click', closeGameModal);
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && gameModal && gameModal.style.display === 'flex') closeGameModal();
      });
      if (englishBtn) englishBtn.addEventListener('click', () => {
        closeGameModal();
        window.location.href = encodeURI('english_online_games.php');
      });
      if (mathBtn) mathBtn.addEventListener('click', () => {
        closeGameModal();
        window.location.href = encodeURI('math_online_games.php');
      });

      const worksheetsTriggers = document.querySelectorAll('.worksheets-trigger');
      const worksheetsModal = document.getElementById('worksheetsModal');
      const closeWorksheetsModalBtn = document.getElementById('closeWorksheetsModalBtn');
      const worksheetsModalOverlay = document.querySelector('#worksheetsModal .modal-overlay');

      function openWorksheetsModal(event) {
        if (event) {
          event.preventDefault();
          event.stopPropagation();
        }
        if (worksheetsModal) {
          worksheetsModal.style.display = 'flex';
          document.body.style.overflow = 'hidden';
        }
      }

      function closeWorksheetsModal() {
        if (worksheetsModal) worksheetsModal.style.display = 'none';
        document.body.style.overflow = '';
      }
      worksheetsTriggers.forEach(trigger => trigger.addEventListener('click', openWorksheetsModal));
      if (closeWorksheetsModalBtn) closeWorksheetsModalBtn.addEventListener('click', closeWorksheetsModal);
      if (worksheetsModalOverlay) worksheetsModalOverlay.addEventListener('click', closeWorksheetsModal);
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && worksheetsModal && worksheetsModal.style.display === 'flex') closeWorksheetsModal();
      });
      const wordFamiliesBtn = document.getElementById('wordFamiliesBtn');
      const sightWordsBtn = document.getElementById('sightWordsBtn');
      const mathWorksheetsBtn = document.getElementById('mathWorksheetsBtn');
      const englishWorksheetsBtn = document.getElementById('englishWorksheetsBtn');
      if (wordFamiliesBtn) wordFamiliesBtn.addEventListener('click', () => {
        closeWorksheetsModal();
        window.location.href = 'Word_Families_worksheets.php';
      });
      if (sightWordsBtn) sightWordsBtn.addEventListener('click', () => {
        closeWorksheetsModal();
        window.location.href = 'Sight_words_Worksheets.php';
      });
      if (mathWorksheetsBtn) mathWorksheetsBtn.addEventListener('click', () => {
        closeWorksheetsModal();
        window.location.href = 'Math_Worksheets.php';
      });
      if (englishWorksheetsBtn) englishWorksheetsBtn.addEventListener('click', () => {
        closeWorksheetsModal();
        window.location.href = 'English_Worksheets.php';
      });

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
          document.body.style.overflow = 'hidden';
        }
      }

      function closePresentationsModal() {
        if (presentationsModal) presentationsModal.style.display = 'none';
        document.body.style.overflow = '';
      }
      presentationsTriggers.forEach(trigger => trigger.addEventListener('click', openPresentationsModal));
      if (closePresentationsModalBtn) closePresentationsModalBtn.addEventListener('click', closePresentationsModal);
      if (presentationsModalOverlay) presentationsModalOverlay.addEventListener('click', closePresentationsModal);
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && presentationsModal && presentationsModal.style.display === 'flex') closePresentationsModal();
      });
      const presentationsWordFamilies = document.getElementById('presentationsWordFamiliesBtn');
      const presentationsSightWords = document.getElementById('presentationsSightWordsBtn');
      const presentationsMath = document.getElementById('presentationsMathBtn');
      const presentationsEnglish = document.getElementById('presentationsEnglishBtn');
      if (presentationsWordFamilies) presentationsWordFamilies.addEventListener('click', () => {
        closePresentationsModal();
        window.location.href = 'Fun_Presentations_word_Families.php';
      });
      if (presentationsSightWords) presentationsSightWords.addEventListener('click', () => {
        closePresentationsModal();
        window.location.href = 'Fun_Presentations_Sight_words.php';
      });
      if (presentationsMath) presentationsMath.addEventListener('click', () => {
        closePresentationsModal();
        window.location.href = 'Fun_Presentations_Math.php';
      });
      if (presentationsEnglish) presentationsEnglish.addEventListener('click', () => {
        closePresentationsModal();
        window.location.href = 'Fun_Presentations_English.php';
      });
    })();
  </script>
</body>

</html>
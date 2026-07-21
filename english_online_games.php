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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <title>Albushra World | Play & Learn Kindergarten Games</title>
  <!-- Google Fonts + Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Baloo+2:wght@500;600;700;800&family=Kalam:wght@300;400;700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: #fffaf5;
      background-image: radial-gradient(circle at 10% 30%, rgba(255, 215, 150, 0.2) 2%, transparent 2.5%);
      background-size: 35px 35px;
      overflow-x: hidden;
      font-family: 'Quicksand', 'Poppins', sans-serif;
    }

    /* ========== STICKY NAVBAR ========== */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 40px;
      background: rgba(255, 250, 240, 0.96);
      backdrop-filter: blur(8px);
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.02);
      flex-wrap: wrap;
      gap: 12px;
      border-bottom: 3px solid #ffb347;
    }

    .logo {
      display: flex;
      gap: 14px;
      align-items: center;
      background: rgba(255, 235, 205, 0.5);
      padding: 5px 18px 5px 12px;
      border-radius: 60px;
    }

    .star {
      font-size: 36px;
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
      color: #e34d3b;
      font-weight: 800;
      font-size: 1.25rem;
      letter-spacing: -0.3px;
      font-family: 'Baloo 2', cursive;
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

    /* Desktop navigation */
    nav {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      transition: all 0.3s ease;
    }

    nav a {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      color: #4a3b2c;
      font-weight: 700;
      font-size: 0.85rem;
      padding: 8px 10px;
      border-radius: 50px;
      transition: all 0.25s ease;
      background: rgba(255, 241, 220, 0.4);
      backdrop-filter: blur(2px);
      font-family: 'Baloo 2', cursive;
    }

    nav a i,
    nav a span {
      font-size: 1rem;
    }

    nav a:hover {
      background: #ffd966;
      color: #c43a1b;
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    nav .active {
      background: #ff8c42;
      color: white;
      box-shadow: 0 4px 10px rgba(255, 140, 66, 0.4);
    }

    /* Hamburger menu (mobile) */
    .hamburger {
      display: none;
      font-size: 2rem;
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
      color: white;
      transform: scale(1.02);
    }

    /* Mobile menu overlay */
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
      color: #4a2e1f;
      background: rgba(255, 220, 170, 0.8);
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

    /* Hero section */
    .hero {
      height: 85vh;
      min-height: 500px;
      width: 100%;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      overflow: hidden;
      border-radius: 0 0 50px 50px;
      margin-bottom: 10px;
    }

    .hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background: url("https://images.pexels.com/photos/8613089/pexels-photo-8613089.jpeg?auto=compress&cs=tinysrgb&w=1600");
      background-size: cover;
      background-position: center 20%;
      background-repeat: no-repeat;
      z-index: -2;
    }

    .hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255, 140, 66, 0.6), rgba(243, 93, 83, 0.65));
      z-index: -1;
    }

    .hero-content {
      max-width: 850px;
      padding: 20px;
      z-index: 2;
      animation: bounceUp 0.9s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    }

    @keyframes bounceUp {
      0% {
        opacity: 0;
        transform: translateY(40px);
      }

      70% {
        transform: translateY(-6px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .hero-content h1 {
      font-size: 3.2rem;
      font-weight: 800;
      margin-bottom: 15px;
      text-shadow: 4px 4px 12px rgba(0, 0, 0, 0.3);
      font-family: 'Baloo 2', cursive;
      letter-spacing: 1px;
    }

    .hero-content h2 {
      font-size: 1.9rem;
      font-weight: 600;
      margin-bottom: 15px;
      color: #fff0b5;
      text-shadow: 2px 2px 0 #c43a1b;
    }

    .hero-content p {
      font-size: 1.25rem;
      line-height: 1.5;
      font-weight: 500;
      background: rgba(0, 0, 0, 0.25);
      display: inline-block;
      padding: 8px 24px;
      border-radius: 50px;
      backdrop-filter: blur(3px);
    }

    @media (max-width: 780px) {
      .hero-content h1 {
        font-size: 2.4rem;
      }

      .hero-content h2 {
        font-size: 1.6rem;
      }

      .hero-content p {
        font-size: 1rem;
      }

      .hero {
        min-height: 450px;
      }
    }

    /* Games grid */
    .games-section {
      max-width: 1400px;
      margin: 40px auto 20px;
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
      margin-bottom: 30px;
    }

    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 28px;
      padding: 0;
    }

    .card {
      border-radius: 38px;
      padding: 22px 16px 20px;
      text-align: center;
      color: #2d2b26;
      position: relative;
      overflow: hidden;
      box-shadow: 0 18px 28px -8px rgba(0, 0, 0, 0.2);
      transition: all 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1);
      background: white;
      border: 2px solid rgba(255, 255, 200, 0.6);
    }

    .card:hover {
      transform: translateY(-12px) scale(1.02);
      box-shadow: 0 28px 36px -12px rgba(0, 0, 0, 0.3);
    }

    .icon {
      font-size: 52px;
      margin-bottom: 12px;
      display: inline-block;
      background: rgba(255, 255, 240, 0.4);
      padding: 8px;
      border-radius: 60px;
    }

    .title {
      font-size: 1.25rem;
      font-weight: 800;
      margin: 12px 0 8px;
      font-family: 'Baloo 2', cursive;
      color: #1e2f3a;
    }

    .badge {
      position: absolute;
      top: 14px;
      left: 16px;
      background: rgba(0, 0, 0, 0.25);
      backdrop-filter: blur(6px);
      padding: 5px 12px;
      border-radius: 40px;
      font-size: 11px;
      font-weight: bold;
      color: white;
    }

    .card a {
      display: inline-block;
      margin-top: 16px;
      padding: 10px 28px;
      border-radius: 60px;
      text-decoration: none;
      font-weight: 800;
      background: white;
      color: #2c3e50;
      transition: 0.25s;
      font-family: 'Baloo 2', cursive;
      font-size: 1rem;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .card a:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Card gradients */
    .card.c1 {
      background: linear-gradient(145deg, #FFD6B0, #FFE0B5);
      border-bottom: 8px solid #ff9f4a;
    }

    .card.c2 {
      background: linear-gradient(145deg, #B5EAD7, #C7F0DB);
      border-bottom: 8px solid #5fa88c;
    }

    .card.c3 {
      background: linear-gradient(145deg, #FFC8DD, #FFD0E4);
      border-bottom: 8px solid #f48fb1;
    }

    .card.c4 {
      background: linear-gradient(145deg, #A0E7E5, #B5F2F0);
      border-bottom: 8px solid #2aa19b;
    }

    .card.c5 {
      background: linear-gradient(145deg, #FFAEBC, #FFC0CE);
      border-bottom: 8px solid #e85d7a;
    }

    .card.c6 {
      background: linear-gradient(145deg, #D4F1F9, #E2F7FF);
      border-bottom: 8px solid #5dade2;
    }

    .card.c7 {
      background: linear-gradient(145deg, #FBE7C6, #FFF0D4);
      border-bottom: 8px solid #f4b942;
    }

    .card.c8 {
      background: linear-gradient(145deg, #B4F0C0, #CBF5D4);
      border-bottom: 8px solid #46b36b;
    }

    .card.c9 {
      background: linear-gradient(145deg, #FFDAC1, #FFE5CF);
      border-bottom: 8px solid #e68a2e;
    }

    .card.c10 {
      background: linear-gradient(145deg, #E0BBE4, #F0D1F5);
      border-bottom: 8px solid #b980c5;
    }

    .card.c11 {
      background: linear-gradient(145deg, #FEC8D8, #FFD6E3);
      border-bottom: 8px solid #ff77a9;
    }

    .card.c12 {
      background: linear-gradient(145deg, #C9E4DE, #D8F0EA);
      border-bottom: 8px solid #569f8b;
    }

    .card.c13 {
      background: linear-gradient(145deg, #FFF3B0, #FFF9D7);
      border-bottom: 8px solid #e0b32b;
    }

    .card.c14 {
      background: linear-gradient(145deg, #CCE7E8, #DCF4F5);
      border-bottom: 8px solid #389e9b;
    }

    .card.c15 {
      background: linear-gradient(145deg, #FADADD, #FFE8EC);
      border-bottom: 8px solid #e6878f;
    }

    .card.c16 {
      background: linear-gradient(145deg, #C5E99B, #D8F0B6);
      border-bottom: 8px solid #86b549;
    }

    .card.c17 {
      background: linear-gradient(145deg, #FBC8B5, #FFD9C9);
      border-bottom: 8px solid #e2825a;
    }

    .card.c18 {
      background: linear-gradient(145deg, #B2F0E5, #C8F9EF);
      border-bottom: 8px solid #3ba390;
    }

    .card.c19 {
      background: linear-gradient(145deg, #EED5FF, #F7E3FF);
      border-bottom: 8px solid #bb86fc;
    }

    .card.c20 {
      background: linear-gradient(145deg, #FFE2B5, #FFEFCF);
      border-bottom: 8px solid #f9b43a;
    }

    .card.c1 a {
      background: #ff8a5c;
      color: white;
    }

    .card.c2 a {
      background: #42b983;
      color: white;
    }

    .card.c3 a {
      background: #e58cb0;
      color: white;
    }

    .card.c4 a {
      background: #2a9d8f;
      color: white;
    }

    .card.c5 a {
      background: #e76f8c;
      color: white;
    }

    .card.c6 a {
      background: #4d9de0;
      color: white;
    }

    .card.c7 a {
      background: #f4a261;
      color: white;
    }

    .card.c8 a {
      background: #5fad56;
      color: white;
    }

    .card.c9 a {
      background: #f39c4b;
      color: white;
    }

    .card.c10 a {
      background: #b26bc2;
      color: white;
    }

    .card.c11 a {
      background: #f26b8a;
      color: white;
    }

    .card.c12 a {
      background: #3b9e8c;
      color: white;
    }

    .card.c13 a {
      background: #e5b83c;
      color: #2c2c2c;
    }

    .card.c14 a {
      background: #349f9a;
      color: white;
    }

    .card.c15 a {
      background: #e27c86;
      color: white;
    }

    .card.c16 a {
      background: #7eb347;
      color: white;
    }

    .card.c17 a {
      background: #e27a4d;
      color: white;
    }

    .card.c18 a {
      background: #358f83;
      color: white;
    }

    .card.c19 a {
      background: #b27fd8;
      color: white;
    }

    .card.c20 a {
      background: #edaa3a;
      color: #2d2b26;
    }

    @media (max-width: 550px) {
      .container {
        grid-template-columns: 1fr;
      }

      .section-heading {
        font-size: 1.8rem;
        padding: 6px 20px;
      }

      .card {
        padding: 18px;
      }
    }

    /* ========== MODAL STYLES (Online Games Choice) ========== */
    .online-modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(6px);
      z-index: 3000;
      display: flex;
      align-items: center;
      justify-content: center;
      visibility: hidden;
      opacity: 0;
      transition: visibility 0.2s, opacity 0.2s ease;
    }

    .online-modal-overlay.active {
      visibility: visible;
      opacity: 1;
    }

    .modal-choice-container {
      background: linear-gradient(145deg, #fffaf0, #fff5e6);
      max-width: 550px;
      width: 90%;
      border-radius: 70px;
      padding: 30px 20px 40px;
      text-align: center;
      box-shadow: 0 35px 45px rgba(0, 0, 0, 0.3), 0 0 0 8px #ffd96688;
      border: 3px solid #ffb347;
      position: relative;
      animation: popModal 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);
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

    .modal-choice-container h2 {
      font-family: 'Baloo 2', cursive;
      font-size: 2.2rem;
      color: #d43f1d;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }

    .modal-choice-container p {
      font-size: 1.2rem;
      color: #7b5a3b;
      margin-bottom: 28px;
      font-weight: 500;
    }

    .choice-buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin: 15px 0 20px;
    }

    .choice-btn {
      background: #ffc48a;
      border: none;
      padding: 16px 28px;
      border-radius: 60px;
      font-size: 1.4rem;
      font-weight: 800;
      font-family: 'Baloo 2', cursive;
      color: #3e2a1f;
      cursor: pointer;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 14px;
      box-shadow: 0 6px 0 #b4622a;
      transform: translateY(-2px);
    }

    .choice-btn i {
      font-size: 1.8rem;
    }

    .choice-btn:active {
      transform: translateY(4px);
      box-shadow: 0 2px 0 #b4622a;
    }

    .choice-btn.english-btn {
      background: #69c0e0;
      color: #1f3b45;
      box-shadow: 0 6px 0 #3a7b92;
    }

    .choice-btn.math-btn {
      background: #f9b43a;
      color: #462709;
      box-shadow: 0 6px 0 #bc6f25;
    }

    .choice-btn:hover {
      filter: brightness(1.02);
      transform: scale(1.02) translateY(-2px);
    }

    .close-modal-btn {
      background: #ffaa77;
      border: none;
      font-size: 1rem;
      padding: 8px 24px;
      border-radius: 40px;
      font-weight: bold;
      color: #5a2e12;
      cursor: pointer;
      margin-top: 10px;
      transition: 0.2s;
      font-family: 'Quicksand', sans-serif;
    }

    .close-modal-btn:hover {
      background: #e5894a;
      color: white;
    }

    /* ========== MODAL FOR WORKSHEETS & PRESENTATIONS ========== */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(6px);
      z-index: 3001;
      display: none;
      align-items: center;
      justify-content: center;
    }

    .modal-overlay.active {
      display: flex;
    }

    .modal-content {
      background: #fffef7;
      max-width: 500px;
      width: 90%;
      border-radius: 60px;
      padding: 28px 20px 35px;
      text-align: center;
      border: 4px solid #ffb347;
      box-shadow: 0 30px 40px rgba(0, 0, 0, 0.25);
      animation: popModal 0.3s ease;
      position: relative;
    }

    .modal-content h2 {
      font-family: 'Baloo 2', cursive;
      font-size: 2rem;
      color: #e65c2e;
      margin-bottom: 8px;
    }

    .modal-content p {
      font-size: 1rem;
      color: #5a4a3a;
      margin-bottom: 25px;
    }

    .modal-options {
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin: 10px 0;
    }

    .modal-option {
      border: none;
      background: #ffecdd;
      padding: 14px 12px;
      border-radius: 60px;
      font-size: 1.2rem;
      font-weight: 700;
      color: #b64926;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      cursor: pointer;
      transition: all 0.2s;
      font-family: 'Baloo 2', cursive;
      border: 2px solid #ffc896;
    }

    .modal-option i {
      font-size: 1.5rem;
    }

    .modal-option:hover {
      transform: translateY(-4px);
      background: #ffddbb;
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
    }

    .close-modal {
      position: absolute;
      top: 12px;
      right: 20px;
      font-size: 28px;
      background: #ffe1c6;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #e63946;
      transition: 0.2s;
    }

    .close-modal:hover {
      background: #ffb347;
      color: white;
      transform: rotate(90deg);
    }

    @media (max-width: 550px) {
      .modal-content h2 {
        font-size: 1.6rem;
      }
      .modal-option {
        font-size: 1rem;
        padding: 10px;
      }
    }

    /* Footer */
    .footer {
      background: #f07c5c;
      background-image: repeating-linear-gradient(45deg, #ffb47b10 0px, #ffb47b10 2px, transparent 2px, transparent 8px);
      color: #fff4e6;
      padding: 2rem 2rem 1rem;
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
      gap: 2rem;
    }

    .footer-logo {
      display: flex;
      align-items: center;
      gap: 15px;
      background: rgba(255, 235, 200, 0.2);
      padding: 8px 25px;
      border-radius: 60px;
    }

    .footer-logo .star {
      font-size: 32px;
      animation: starGlow 2s infinite alternate;
    }

    .footer-logo h4 {
      font-family: 'Kalam', cursive;
      font-size: 1.4rem;
      font-weight: 700;
      color: #ffefbf;
    }

    .footer-social {
      display: flex;
      gap: 28px;
      flex-wrap: wrap;
    }

    .footer-social a {
      color: #fff0da;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: rgba(255, 255, 210, 0.1);
      padding: 8px 20px;
      border-radius: 50px;
      transition: 0.25s;
    }

    .footer-social a i {
      font-size: 1.4rem;
    }

    .footer-social a:hover {
      background: #ffb347;
      color: #3a280f;
      transform: translateY(-3px);
    }

    .footer-copyright {
      margin-top: 2rem;
      font-size: 0.75rem;
      opacity: 0.85;
      border-top: 2px dotted #ffcf9a;
      padding-top: 1rem;
    }

    @media (max-width: 780px) {
      .footer-container {
        flex-direction: column;
        text-align: center;
      }

      .footer-social a {
        padding: 5px 16px;
        font-size: 0.8rem;
      }
    }
  </style>
</head>

<body>

  <!-- ========== STICKY NAVBAR ========== -->
  <header class="navbar">
    <div class="logo">
      <span class="star">🌟</span>
      <div>
        <h3>Albushra's World</h3>
        <p>✨ kinder joy ✨</p>
      </div>
    </div>
    <!-- Desktop navigation -->
    <nav id="desktopNav">
      <a href="index.php">🏠 Home</a>
      <!-- زر Worksheets يفتح مودال الـ Worksheets -->
      <a href="#" id="worksheetsMainLink">📄 Worksheets</a>
      <!-- زر Presentations يفتح مودال الـ Presentations -->
      <a href="#" id="presentationsMainLink">🎭 Fun Presentations</a>
      <a href="#" id="onlineGamesMainLink">🎮 Online Games</a>
      <a href="english_flashcards.php">📘 English Flashcards</a>
      <a href="math_flashcards.php">🧮 Math Flashcards</a>

      <?php if ($isLoggedIn && $userRole === 'admin'): ?>
        <a href="admin_dashboard.php" id="dashboardLink">🛠 Dashboard</a>
      <?php endif; ?>

      <?php if ($isLoggedIn): ?>
        <a href="logout.php" id="logoutBtn">🚪 Logout</a>
      <?php else: ?>
        <a href="login.php">🔐 Login</a>
        <a href="register.php">📝 Register</a>
      <?php endif; ?>
    </nav>
    <!-- Hamburger Icon -->
    <div class="hamburger" id="hamburgerIcon">
      <i class="fas fa-bars"></i>
    </div>
  </header>

  <!-- Mobile menu overlay (fullscreen) -->
  <div class="mobile-nav" id="mobileNavMenu">
    <div class="close-menu" id="closeMenuBtn"><i class="fas fa-times"></i></div>
    <a href="index.php"><i class="fas fa-home"></i> Home</a>
    <a href="#" id="mobileWorksheetsLink"><i class="fas fa-book-open"></i> Worksheets</a>
    <a href="#" id="mobilePresentationsLink"><i class="fas fa-chalkboard-user"></i> Fun Presentations</a>
    <a href="#" id="mobileOnlineGamesLink"><i class="fas fa-gamepad"></i> Online Games</a>
    <a href="english_flashcards.php"><i class="fas fa-language"></i> English Flashcards</a>
    <a href="math_flashcards.php"><i class="fas fa-calculator"></i> Math Flashcards</a>

    <?php if ($isLoggedIn && $userRole === 'admin'): ?>
      <a href="admin_dashboard.php" id="dashboardLink">🛠 Dashboard</a>
    <?php endif; ?>

    <?php if ($isLoggedIn): ?>
      <a href="logout.php" id="logoutBtn">🚪 Logout</a>
    <?php else: ?>
      <a href="login.php">🔐 Login</a>
      <a href="register.php">📝 Register</a>
    <?php endif; ?>
  </div>

  <!-- ========== HERO SECTION ========== -->
  <section class="hero">
    <div class="hero-content">
      <h1>🎮 Activities & Games</h1>
      <h2>Let’s Play, Learn & Grow!</h2>
      <p>Tap, click & explore — joyful learning adventures for little superstars ✨</p>
    </div>
  </section>

  <!-- ========== GAMES GRID (20 Interactive Cards) ========== -->
  <div class="games-section">
    <div class="heading-wrapper">
      <div class="section-heading">🎲Playful Learning Games🎲</div>
    </div>

    <div class="container">
      <?php
      require_once 'db.php';

      $stmt = $pdo->query("SELECT * FROM game_settings");
      $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $i = 1;
      foreach ($games as $game) {
      ?>
        <div class="card c<?= $i ?>">
          <div class="badge">🎮</div>
          <div class="icon">🔤🎯</div>
          <div class="title"><?= htmlspecialchars($game['gName']) ?></div>
          <a href="<?= htmlspecialchars($game['gLink']) ?>" target="_blank">▶ Play Now</a>
        </div>
      <?php
        $i++;
      }
      ?>
    </div>
  </div>

  <!-- ========== MODAL: WORKSHEETS ========== -->
  <div id="worksheetsModal" class="modal-overlay">
    <div class="modal-content">
      <div class="close-modal" id="closeWorksheetsModalBtn">&times;</div>
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

  <!-- ========== MODAL: FUN PRESENTATIONS ========== -->
  <div id="presentationsModal" class="modal-overlay">
    <div class="modal-content">
      <div class="close-modal" id="closePresentationsModalBtn">&times;</div>
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

  <!-- ========== MODAL: ONLINE GAMES (English / Math) ========== -->
  <div id="onlineGameModal" class="online-modal-overlay">
    <div class="modal-choice-container">
      <h2><i class="fas fa-gamepad"></i> Choose Adventure!</h2>
      <p>Which games would you like to play? </p>
      <div class="choice-buttons">
        <button id="englishGamesBtn" class="choice-btn english-btn"><i class="fas fa-book-open"></i> English Online Games</button>
        <button id="mathGamesBtn" class="choice-btn math-btn"><i class="fas fa-calculator"></i> Math Online Games</button>
      </div>
      <button id="closeModalBtn" class="close-modal-btn"><i class="fas fa-arrow-left"></i> Maybe Later</button>
    </div>
  </div>

  <!-- ========== FOOTER ========== -->
  <?php include 'footer.php'; ?>

  <script>
    // Mobile menu functionality
    const hamburger = document.getElementById('hamburgerIcon');
    const mobileMenu = document.getElementById('mobileNavMenu');
    const closeMenuBtn = document.getElementById('closeMenuBtn');

    function openMobileMenu() {
      mobileMenu.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
      mobileMenu.classList.remove('open');
      document.body.style.overflow = '';
    }
    if (hamburger) hamburger.addEventListener('click', openMobileMenu);
    if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeMobileMenu);

    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', () => {
        closeMobileMenu();
      });
    });

    // ---------- MODAL LOGIC FOR WORKSHEETS ----------
    const worksheetsModal = document.getElementById('worksheetsModal');
    const worksheetsTriggers = document.querySelectorAll('#worksheetsMainLink, #mobileWorksheetsLink');
    const closeWorksheetsModalBtn = document.getElementById('closeWorksheetsModalBtn');

    function openWorksheetsModal(e) {
      if (e) e.preventDefault();
      if (worksheetsModal) worksheetsModal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeWorksheetsModal() {
      if (worksheetsModal) worksheetsModal.classList.remove('active');
      document.body.style.overflow = '';
    }

    worksheetsTriggers.forEach(trigger => trigger.addEventListener('click', openWorksheetsModal));
    if (closeWorksheetsModalBtn) closeWorksheetsModalBtn.addEventListener('click', closeWorksheetsModal);
    if (worksheetsModal) {
      worksheetsModal.addEventListener('click', function(e) {
        if (e.target === worksheetsModal) closeWorksheetsModal();
      });
    }

    // Redirects for Worksheets
    document.getElementById('wordFamiliesBtn')?.addEventListener('click', () => {
      closeWorksheetsModal();
      window.location.href = 'Word_Families_worksheets.php';
    });
    document.getElementById('sightWordsBtn')?.addEventListener('click', () => {
      closeWorksheetsModal();
      window.location.href = 'Sight_words_Worksheets.php';
    });
    document.getElementById('mathWorksheetsBtn')?.addEventListener('click', () => {
      closeWorksheetsModal();
      window.location.href = 'Math_Worksheets.php';
    });
    document.getElementById('englishWorksheetsBtn')?.addEventListener('click', () => {
      closeWorksheetsModal();
      window.location.href = 'English_Worksheets.php';
    });

    // ---------- MODAL LOGIC FOR PRESENTATIONS ----------
    const presentationsModal = document.getElementById('presentationsModal');
    const presentationsTriggers = document.querySelectorAll('#presentationsMainLink, #mobilePresentationsLink');
    const closePresentationsModalBtn = document.getElementById('closePresentationsModalBtn');

    function openPresentationsModal(e) {
      if (e) e.preventDefault();
      if (presentationsModal) presentationsModal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closePresentationsModal() {
      if (presentationsModal) presentationsModal.classList.remove('active');
      document.body.style.overflow = '';
    }

    presentationsTriggers.forEach(trigger => trigger.addEventListener('click', openPresentationsModal));
    if (closePresentationsModalBtn) closePresentationsModalBtn.addEventListener('click', closePresentationsModal);
    if (presentationsModal) {
      presentationsModal.addEventListener('click', function(e) {
        if (e.target === presentationsModal) closePresentationsModal();
      });
    }

    // Redirects for Presentations
    document.getElementById('presentationsWordFamiliesBtn')?.addEventListener('click', () => {
      closePresentationsModal();
      window.location.href = 'Fun_Presentations_word_Families.php';
    });
    document.getElementById('presentationsSightWordsBtn')?.addEventListener('click', () => {
      closePresentationsModal();
      window.location.href = 'Fun_Presentations__Sight_words.php';
    });
    document.getElementById('presentationsMathBtn')?.addEventListener('click', () => {
      closePresentationsModal();
      window.location.href = 'Fun_Presentations__Math.php';
    });
    document.getElementById('presentationsEnglishBtn')?.addEventListener('click', () => {
      closePresentationsModal();
      window.location.href = 'Fun_Presentations_English.php';
    });

    // ---------- MODAL LOGIC FOR ONLINE GAMES (English/Math) ----------
    const modalOverlay = document.getElementById('onlineGameModal');
    const desktopOnlineLink = document.getElementById('onlineGamesMainLink');
    const mobileOnlineLink = document.getElementById('mobileOnlineGamesLink');
    const englishBtn = document.getElementById('englishGamesBtn');
    const mathBtn = document.getElementById('mathGamesBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');

    function openGameModal(event) {
      if (event) event.preventDefault();
      if (modalOverlay) {
        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    }

    function closeGameModal() {
      if (modalOverlay) {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = '';
      }
    }

    function goToEnglishGames() {
      window.open("english_online_games.php", "_blank") || (window.location.href = "english_online_games.php");
      closeGameModal();
    }

    function goToMathGames() {
      window.open("math_online_games.php", "_blank") || (window.location.href = "math_online_games.php");
      closeGameModal();
    }

    if (desktopOnlineLink) desktopOnlineLink.addEventListener('click', openGameModal);
    if (mobileOnlineLink) mobileOnlineLink.addEventListener('click', openGameModal);
    if (englishBtn) englishBtn.addEventListener('click', goToEnglishGames);
    if (mathBtn) mathBtn.addEventListener('click', goToMathGames);
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeGameModal);
    if (modalOverlay) {
      modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) closeGameModal();
      });
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        if (worksheetsModal?.classList.contains('active')) closeWorksheetsModal();
        if (presentationsModal?.classList.contains('active')) closePresentationsModal();
        if (modalOverlay?.classList.contains('active')) closeGameModal();
      }
    });
  </script>
</body>

</html>
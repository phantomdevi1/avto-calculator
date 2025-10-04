<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Главная — Финансовые расчёты TANK</title>
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <img src="img/logo.png" alt="" class="index_logo">
    <h2>Добро пожаловать, <?= htmlspecialchars($_SESSION['user']) ?>!</h2>
    <p class="security_status_text">Это защищённая страница личного кабинета.</p>

    <div class="container_home_href">      
      <a href="osago.php" class="block_href">
        <img src="img/osago_icon.png" alt="ОСАГО">
        <span>Расчёт ОСАГО</span>
      </a>

      <a href="kasko.php" class="block_href">
        <img src="img/casco_icon.png" alt="КАСКО">
        <span>Расчёт КАСКО</span>
      </a>

      <a href="credit.php" class="block_href">
        <img src="img/credit_icon.png" alt="Кредит">
        <span>Расчёт автокредита</span>
      </a>

      <a href="tradein.php" class="block_href">
        <img src="img/tradein_icon.png" alt="TRADE-IN">
        <span>Расчёт TRADE-IN</span>
      </a>

    </div>

    <a class="logout_btn" href="logout.php">Выйти</a>
  </div>
</body>
</html>

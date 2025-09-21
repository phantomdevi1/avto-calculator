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
  <title>Главная</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Добро пожаловать, <?= htmlspecialchars($_SESSION['user']) ?>!</h2>
    <p class="security_status_text">Это защищённая страница.</p>
    <div class="container_home_href">
      <a href="osago.php" class="block_href">Расчёт ОСАГО</a>
      <a href="kasko.php" class="block_href">Расчёт КАСКО</a>
      <a href="credit.php" class="block_href">Расчёт автокредита</a>
      <a href="tradein.php" class="block_href">Расчёт TRADE-IN</a>
    </div>
    <a class="logout_btn" href="logout.php">Выйти</a>
  </div>
</body>
</html>

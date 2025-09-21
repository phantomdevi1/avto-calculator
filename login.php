<?php
session_start();
require 'config.php';

// Если уже авторизован — сразу на index.php
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Заполните все поля";
    } else {
        // Подготовленное выражение с mysqli
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            // Сравнение plain-text паролей (НЕБЕЗОПАСНО)
            if ($user && $password === $user['password']) {
                // Устанавливаем сессию (можно хранить id и username)
                $_SESSION['user'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php");
                exit;
            } else {
                $error = "❌ Неверный логин или пароль";
            }
        } else {
            $error = "Ошибка сервера (не удалось подготовить запрос)";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Авторизация</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
  <div class="login-box">
    <h2>Авторизация</h2>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" autocomplete="off">
      <input type="text" name="username" placeholder="Логин" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <button type="submit">Войти</button>
    </form>
  </div>
</body>
</html>

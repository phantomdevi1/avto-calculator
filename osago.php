<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$result = '';
if (isset($_POST['calculate'])) {
    // Данные формы
    $base = floatval($_POST['base']); // базовый тариф
    $region = $_POST['region'];
    $age = intval($_POST['age']);
    $experience = intval($_POST['experience']);
    $drivers = $_POST['drivers'];
    $season = $_POST['season'];
    $accidents = $_POST['accidents'];
    $power = intval($_POST['power']); // мощность в л.с.

    // --- Коэффициенты ---
    // КТ: регион
    $kt = ($region === "Москва" || $region === "Санкт-Петербург") ? 2 : 1;

    // КВС: возраст/стаж
    if ($age < 22 || $experience < 3) {
        $kvs = 1.8;
    } else {
        $kvs = 1;
    }

    // КО: количество водителей
    $ko = ($drivers === "unlimited") ? 1.8 : 1;

    // КС: сезонность
    $ks = ($season === "year") ? 1 : 0.7;

    // КБМ: аварии
    $kbm = ($accidents === "yes") ? 1.55 : 0.9;

    // КМ: мощность двигателя
    if ($power <= 50) $km = 0.6;
    elseif ($power <= 70) $km = 1.0;
    elseif ($power <= 100) $km = 1.1;
    elseif ($power <= 120) $km = 1.2;
    elseif ($power <= 150) $km = 1.4;
    else $km = 1.6;

    // КН (штрафной) — для простоты всегда 1
    $kn = 1;

    // Итоговая цена
    $price = $base * $kbm * $kt * $kvs * $ko * $ks * $km * $kn;

    $result = "
    <div class='result' id='result-block'>
        <h2>Расчет стоимости ОСАГО</h2>
        <p>Регион: <strong>$region</strong></p>
        <p>Возраст: <strong>$age лет</strong>, Стаж: <strong>$experience лет</strong></p>
        <p>Мощность автомобиля: <strong>$power л.с.</strong></p>
        <p>Водители: <strong>".($drivers === 'unlimited' ? 'Неограниченно' : 'Ограниченный список')."</strong></p>
        <p>Сезонность: <strong>".($season === 'year' ? 'Круглый год' : 'На сезон')."</strong></p>
        <p>Аварии: <strong>".($accidents === 'yes' ? 'Были' : 'Не было')."</strong></p>
        <p><strong>Стоимость ОСАГО: ".number_format($price,2,',',' ')." руб.</strong></p>

        <form method='post' action='generate_osago.php' target='_blank'>
            <input type='hidden' name='region' value='$region'>
            <input type='hidden' name='age' value='$age'>
            <input type='hidden' name='experience' value='$experience'>
            <input type='hidden' name='power' value='$power'>
            <input type='hidden' name='drivers' value='$drivers'>
            <input type='hidden' name='season' value='$season'>
            <input type='hidden' name='accidents' value='$accidents'>
            <input type='hidden' name='price' value='$price'>
            <button type='submit' class='pdf-button'>Сформировать PDF</button>
        </form>

        <button type='button' class='back-button' id='clear-btn'>← Назад</button>
    </div>
    ";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Калькулятор ОСАГО</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
      <header>
        <a class="toolbar_href" href="osago.php">Расчёт ОСАГО</a>
        <a class="toolbar_href" href="kasko.php">Расчёт КАСКО</a>
        <a class="toolbar_href" href="index.php">Главная</a>
        <a class="toolbar_href" href="credit.php">Расчёт автокредита</a>
        <a class="toolbar_href" href="tradein.php">Расчёт TRADE-IN</a>
    </header>
<div class="calculator-box">
    <h1>Калькулятор стоимости ОСАГО</h1>

    <form method="post" id="osago-form">
        <label>Базовый тариф (руб.):</label>
        <input type="number" step="0.01" name="base" value="5000" required>

        <label>Регион регистрации:</label>
        <select name="region" required>
            <option value="Москва">Москва</option>
            <option value="Санкт-Петербург">Санкт-Петербург</option>
            <option value="Тверь">Тверь</option>
            <option value="Другой">Другой</option>
        </select>

        <label>Возраст водителя:</label>
        <input type="number" name="age" required>

        <label>Стаж вождения (лет):</label>
        <input type="number" name="experience" required>

        <label>Мощность автомобиля (л.с.):</label>
        <input type="number" name="power" required>

        <label>Количество водителей:</label>
        <select name="drivers" required>
            <option value="limited">Ограниченный список</option>
            <option value="unlimited">Неограниченное количество</option>
        </select>

        <label>Сезонность:</label>
        <select name="season" required>
            <option value="year">Круглый год</option>
            <option value="season">На сезон</option>
        </select>

        <label>Были ли аварии/нарушения:</label>
        <select name="accidents" required>
            <option value="no">Не было</option>
            <option value="yes">Были</option>
        </select>

        <input type="submit" name="calculate" value="Рассчитать">
    </form>

    <?php echo $result; ?>
</div>

<script>
// Кнопка «Назад» — очищаем форму и скрываем результат
document.addEventListener('click', function(e){
    if(e.target && e.target.id === 'clear-btn'){
        document.getElementById('osago-form').reset();
        const resultBlock = document.getElementById('result-block');
        if(resultBlock) resultBlock.remove();
    }
});
</script>
</body>
</html>

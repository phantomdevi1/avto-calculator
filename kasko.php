<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$result = '';
if (isset($_POST['calculate'])) {
    $model = $_POST['model'];
    $year = intval($_POST['year']);
    $price = floatval(str_replace(' ', '', $_POST['price']));
    $driver_age = intval($_POST['driver_age']);
    $experience = intval($_POST['experience']);
    $franchise = floatval(str_replace(' ', '', $_POST['franchise']));
    $accidents = $_POST['accidents'];

    // Безопасное ограничение франшизы (0–100000)
    if ($franchise < 0) $franchise = 0;
    if ($franchise > 100000) $franchise = 100000;

    // Базовая ставка (% от стоимости авто)
    $base_rate = 5.0;

    // Возраст водителя
    if ($driver_age < 25) {
        $base_rate += 2.0; 
    } elseif ($driver_age > 60) {
        $base_rate += 1.0;
    }

    // Стаж
    if ($experience < 3) {
        $base_rate += 2.5;
    } elseif ($experience > 10) {
        $base_rate -= 0.5;
    }

    // Франшиза — снижает ставку максимум на 2%
    if ($franchise > 0) {
        $discount = min($franchise / 50000, 2.0);
        $base_rate -= $discount;
    }

    // Аварии
    if ($accidents === 'yes') {
        $base_rate += 1.5;
    }

    // Расчёт стоимости КАСКО
    $price_calc = $price * $base_rate / 100;

    $result = "
    <div class='result' id='result-block'>
        <h2>Расчёт КАСКО</h2>
        <p>Модель: <strong>$model</strong></p>
        <p>Год выпуска: <strong>$year</strong></p>
        <p>Стоимость автомобиля: <strong>".number_format($price, 2, ',', ' ')." руб.</strong></p>
        <p>Возраст водителя: <strong>$driver_age лет</strong></p>
        <p>Стаж вождения: <strong>$experience лет</strong></p>
        <p>Франшиза: <strong>".number_format($franchise, 0, ',', ' ')." руб.</strong></p>
        <p>Наличие аварий: <strong>".($accidents === 'yes' ? 'Были' : 'Не было')."</strong></p>
        <p><strong>Итоговая стоимость КАСКО: ".number_format($price_calc, 2, ',', ' ')." руб.</strong></p>

        <form method='post' action='generate_kasko.php' target='_blank'>
            <input type='hidden' name='model' value='$model'>
            <input type='hidden' name='year' value='$year'>
            <input type='hidden' name='price' value='$price'>
            <input type='hidden' name='driver_age' value='$driver_age'>
            <input type='hidden' name='experience' value='$experience'>
            <input type='hidden' name='franchise' value='$franchise'>
            <input type='hidden' name='accidents' value='$accidents'>
            <input type='hidden' name='price_calc' value='$price_calc'>
            <button type='submit' class='pdf-button'>Сформировать договор PDF</button>
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
<title>Калькулятор КАСКО</title>
<link rel="stylesheet" href="style.css">
<link rel='shortcut icon' href='img/favicon.png' type='image/x-icon'>
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
    <h1>Калькулятор КАСКО</h1>

    <form method="post" id="kasko-form">
        <label>Модель авто:</label>
        <input type="text" name="model" required>

        <label>Год выпуска:</label>
        <input type="number" name="year" min="1980" max="2025" required>

        <label>Стоимость автомобиля (руб.):</label>
        <input type="text" name="price" id="price" required>

        <label>Возраст водителя:</label>
        <input type="number" name="driver_age" min="18" required>

        <label>Стаж вождения (лет):</label>
        <input type="number" name="experience" min="0" required>

        <label>Франшиза (руб.):</label>
        <input type="number" name="franchise" id="franchise" min="0" max="100000" placeholder="от 0 до 100 000" required>

        <label>Были ли аварии за последние 3 года?</label>
        <select name="accidents">
            <option value="no">Не было</option>
            <option value="yes">Были</option>
        </select>

        <input type="submit" name="calculate" value="Рассчитать" >
    </form>

    <?php echo $result; ?>
</div>

<script>
// Форматирование чисел с пробелами для "Цена авто"
function formatNumber(input) {
    let value = input.value.replace(/\D/g,'');
    input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

function unformatNumber(value) {
    return value.replace(/\s/g, '');
}

document.getElementById('price').addEventListener('input', function() {
    formatNumber(this);
});

// Перед отправкой убираем пробелы
document.getElementById('kasko-form').addEventListener('submit', function(){
    document.getElementById('price').value = unformatNumber(document.getElementById('price').value);

    // Ограничиваем франшизу от 0 до 100000
    const f = document.getElementById('franchise');
    if (f.value < 0) f.value = 0;
    if (f.value > 100000) f.value = 100000;
});

// Кнопка "Назад"
document.addEventListener('click', function(e){
    if(e.target && e.target.id === 'clear-btn'){
        document.getElementById('kasko-form').reset();
        const resultBlock = document.getElementById('result-block');
        if(resultBlock) resultBlock.remove();
    }
});
</script>
</body>
</html>

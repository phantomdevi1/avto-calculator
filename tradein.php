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
    $base_price = floatval(str_replace(' ', '', $_POST['base_price']));
    $mileage = intval($_POST['mileage']);
    $condition = $_POST['condition'];

    $current_year = date("Y");
    $age = $current_year - $year;

    // Начинаем с базовой стоимости
    $final_price = $base_price;

    // Корректировка за возраст (минус 5% за каждый год старше 3 лет)
    if ($age > 3) {
        $final_price -= $base_price * 0.05 * ($age - 3);
    }

    // Корректировка за пробег (свыше 100 000 км – минус 10%, свыше 200 000 км – минус 20%)
    if ($mileage > 200000) {
        $final_price -= $base_price * 0.20;
    } elseif ($mileage > 100000) {
        $final_price -= $base_price * 0.10;
    }

    // Корректировка за состояние
    switch ($condition) {
        case 'excellent': // отличное
            $final_price *= 1.05; // бонус
            break;
        case 'good': // хорошее
            // без изменений
            break;
        case 'satisfactory': // удовлетворительное
            $final_price -= $base_price * 0.10;
            break;
        case 'bad': // плохое
            $final_price -= $base_price * 0.25;
            break;
    }

    // Не меньше 10% от базовой стоимости
    if ($final_price < $base_price * 0.1) {
        $final_price = $base_price * 0.1;
    }

    $result = "
    <div class='result' id='result-block'>
        <h2>Оценка Trade-in</h2>
        <p>Модель: <strong>$model</strong></p>
        <p>Год выпуска: <strong>$year</strong></p>
        <p>Базовая стоимость нового авто: <strong>".number_format($base_price, 2, ',', ' ')." руб.</strong></p>
        <p>Возраст автомобиля: <strong>$age лет</strong></p>
        <p>Пробег: <strong>".number_format($mileage, 0, ',', ' ')." км</strong></p>
        <p>Состояние: <strong>".ucfirst($condition)."</strong></p>
        <p><strong>Итоговая стоимость по программе Trade-in: ".number_format($final_price, 2, ',', ' ')." руб.</strong></p>

        <form method='post' action='generate_tradein.php' target='_blank'>
            <input type='hidden' name='model' value='$model'>
            <input type='hidden' name='year' value='$year'>
            <input type='hidden' name='base_price' value='$base_price'>
            <input type='hidden' name='mileage' value='$mileage'>
            <input type='hidden' name='condition' value='$condition'>
            <input type='hidden' name='final_price' value='$final_price'>
            <button type='submit' class='pdf-button'>Сформировать PDF-оценку</button>
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
<title>Оценка Trade-in</title>
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
    <h1>Калькулятор Trade-in</h1>

    <form method="post" id="tradein-form">
        <label>Модель авто:</label>
        <input type="text" name="model" required>

        <label>Год выпуска:</label>
        <input type="number" name="year" min="1980" max="2025" required>

        <label>Базовая стоимость нового авто (руб.):</label>
        <input type="text" name="base_price" id="base_price" required>

        <label>Пробег (км):</label>
        <input type="number" name="mileage" required>

        <label>Состояние автомобиля:</label>
        <select name="condition">
            <option value="excellent">Отличное</option>
            <option value="good">Хорошее</option>
            <option value="satisfactory">Удовлетворительное</option>
            <option value="bad">Плохое</option>
        </select>

        <input type="submit" name="calculate" value="Рассчитать">
    </form>

    <?php echo $result; ?>
</div>

<script>
// Форматирование чисел с пробелами
function formatNumber(input) {
    let value = input.value.replace(/\D/g,'');
    input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

function unformatNumber(value) {
    return value.replace(/\s/g, '');
}

document.getElementById('base_price').addEventListener('input', function() {
    formatNumber(this);
});

document.getElementById('tradein-form').addEventListener('submit', function(){
    document.getElementById('base_price').value = unformatNumber(document.getElementById('base_price').value);
});

// Кнопка «Назад»
document.addEventListener('click', function(e){
    if(e.target && e.target.id === 'clear-btn'){
        document.getElementById('tradein-form').reset();
        const resultBlock = document.getElementById('result-block');
        if(resultBlock) resultBlock.remove();
    }
});
</script>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$result = '';
if (isset($_POST['calculate'])) {
    $model = $_POST['model'];
    $price = floatval(str_replace(' ','', $_POST['price']));
    $down_payment = floatval(str_replace(' ','', $_POST['down_payment']));
    $term = intval($_POST['term']);
    $rate = floatval($_POST['rate']);

    // Расчеты кредита
    $loan_amount = $price - $down_payment;
    $monthly_rate = $rate / 100 / 12;
    $monthly_payment = $monthly_rate > 0
        ? $loan_amount * ($monthly_rate * pow(1 + $monthly_rate, $term)) / (pow(1 + $monthly_rate, $term) - 1)
        : $loan_amount / $term;
    $total_payment = $monthly_payment * $term;
    $total_interest = $total_payment - $loan_amount;

    // Формируем блок результата
    $result = "
    <div class='result' id='result-block'>
        <h2>Расчет кредита</h2>
        <p>Модель: <strong>$model</strong></p>
        <p>Стоимость автомобиля: <strong>".number_format($price,2,',',' ')." руб.</strong></p>
        <p>Первоначальный взнос: <strong>".number_format($down_payment,2,',',' ')." руб.</strong></p>
        <p>Сумма кредита: <strong>".number_format($loan_amount,2,',',' ')." руб.</strong></p>
        <p>Срок кредита: <strong>$term месяцев</strong></p>
        <p>Процентная ставка: <strong>$rate%</strong></p>
        <p>Ежемесячный платёж: <strong>".number_format($monthly_payment,2,',',' ')." руб.</strong></p>
        <p>Общая сумма выплат: <strong>".number_format($total_payment,2,',',' ')." руб.</strong></p>
        <p>Переплата по процентам: <strong>".number_format($total_interest,2,',',' ')." руб.</strong></p>

        <form method='post' action='generate.php' target='_blank'>
            <input type='hidden' name='model' value='$model'>
            <input type='hidden' name='price' value='".str_replace(' ','',$price)."'>
            <input type='hidden' name='down_payment' value='".str_replace(' ','',$down_payment)."'>
            <input type='hidden' name='term' value='$term'>
            <input type='hidden' name='rate' value='$rate'>
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
<title>Автокредит</title>
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
    <h1>Калькулятор автокредита</h1>

    <form method="post" id="credit-form">
        <label>Модель Tank:</label>
        <select name="model" required>
            <option value="Tank 300">Tank 300</option>
            <option value="Tank 400">Tank 400</option>
            <option value="Tank 500">Tank 500</option>
            <option value="Tank 700">Tank 700</option>
        </select>

        <label>Стоимость автомобиля (руб.):</label>
        <input type="text" name="price" id="price" required>

        <label>Первоначальный взнос (руб.):</label>
        <input type="text" name="down_payment" id="down_payment" required>

        <label>Срок кредита (месяцев):</label>
        <input type="number" name="term" required>

        <label>Процентная ставка (% годовых):</label>
        <input type="number" name="rate" step="0.01" required>

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

document.getElementById('price').addEventListener('input', function() {
    formatNumber(this);
});
document.getElementById('down_payment').addEventListener('input', function() {
    formatNumber(this);
});

document.getElementById('credit-form').addEventListener('submit', function(){
    document.getElementById('price').value = unformatNumber(document.getElementById('price').value);
    document.getElementById('down_payment').value = unformatNumber(document.getElementById('down_payment').value);
});

// Кнопка «Назад» — очищаем форму и скрываем результат
document.addEventListener('click', function(e){
    if(e.target && e.target.id === 'clear-btn'){
        document.getElementById('credit-form').reset();
        const resultBlock = document.getElementById('result-block');
        if(resultBlock) resultBlock.remove();
    }
});
</script>
</body>
</html>

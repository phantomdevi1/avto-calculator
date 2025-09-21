<?php
require_once __DIR__ . '/vendor/autoload.php';
use Mpdf\Mpdf;

$model = $_POST['model'];
$price = floatval($_POST['price']);
$down_payment = floatval($_POST['down_payment']);
$term = intval($_POST['term']);
$rate = floatval($_POST['rate']);

$loan_amount = $price - $down_payment;
$monthly_rate = $rate / 100 / 12;
$monthly_payment = $monthly_rate > 0 
    ? $loan_amount * ($monthly_rate * pow(1 + $monthly_rate, $term)) / (pow(1 + $monthly_rate, $term) - 1)
    : $loan_amount / $term;

$total_payment = $monthly_payment * $term;
$total_interest = $total_payment - $loan_amount;

$html = "
<head>
<title>Документ</title>
</head>
<h1>Договор автокредита</h1>
<p>г. Тверь, «___» ________ 20__ г.</p>
<p>Банк (далее – Кредитор) и Клиент заключили настоящий договор автокредита на следующих условиях:</p>
<h3>1. Предмет договора</h3>
<p>Кредитор предоставляет Клиенту кредит на приобретение автомобиля марки <strong>$model</strong>.</p>
<p>Стоимость автомобиля: <strong>".number_format($price,2,',',' ')." руб.</strong></p>
<p>Первоначальный взнос: <strong>".number_format($down_payment,2,',',' ')." руб.</strong></p>
<h3>2. Условия кредита</h3>
<p>Сумма кредита: <strong>".number_format($loan_amount,2,',',' ')." руб.</strong></p>
<p>Срок кредита: <strong>$term месяцев</strong></p>
<p>Процентная ставка: <strong>$rate%</strong> годовых</p>
<p>Ежемесячный платёж: <strong>".number_format($monthly_payment,2,',',' ')." руб.</strong></p>
<p>Общая сумма выплат: <strong>".number_format($total_payment,2,',',' ')." руб.</strong></p>
<p>Переплата по процентам: <strong>".number_format($total_interest,2,',',' ')." руб.</strong></p>
<h3>3. Подписи сторон</h3>
<p>Кредитор: _____________________________________________________________________</p>
<p>Клиент: _______________________________________________________________________</p>
";

$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('dogovor_autokredita.pdf', 'I'); // открывает PDF в браузере

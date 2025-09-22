<?php
require_once __DIR__ . '/vendor/autoload.php';
use Mpdf\Mpdf;

// Получаем данные из формы
$region = $_POST['region'];
$age = intval($_POST['age']);
$experience = intval($_POST['experience']);
$power = intval($_POST['power']);
$drivers = $_POST['drivers'] === 'unlimited' ? 'Неограниченное количество' : 'Ограниченный список';
$season = $_POST['season'] === 'year' ? 'Круглый год' : 'На сезон';
$accidents = $_POST['accidents'] === 'yes' ? 'Были' : 'Не было';
$price = floatval($_POST['price']);

// Формируем HTML договора
$html = "
<head>
<title>Документ</title>
<link rel='shortcut icon' href='img/favicon.png' type='image/x-icon'>
</head>
<h1>Договор ОСАГО</h1>
<p>г. Тверь, «___» ________ 20__ г.</p>
<p>Страховая компания (далее – Страховщик) и Страхователь заключили настоящий договор ОСАГО на следующих условиях:</p>

<h3>1. Данные страхователя</h3>
<p>Возраст водителя: <strong>$age лет</strong></p>
<p>Стаж вождения: <strong>$experience лет</strong></p>
<p>Регион регистрации: <strong>$region</strong></p>

<h3>2. Данные автомобиля</h3>
<p>Мощность двигателя: <strong>$power л.с.</strong></p>
<p>Количество водителей: <strong>$drivers</strong></p>
<p>Сезонность использования: <strong>$season</strong></p>
<p>Наличие аварий: <strong>$accidents</strong></p>

<h3>3. Стоимость договора</h3>
<p>Итоговая стоимость ОСАГО составляет: <strong>".number_format($price,2,',',' ')." руб.</strong></p>

<h3>4. Подписи сторон</h3>
<p>Страховщик: __________________________________________________________________________</p>
<p>Страхователь: ________________________________________________________________________</p>
";

// Создаем PDF
$mpdf = new Mpdf([
    'default_font' => 'dejavusans'
]);
$mpdf->WriteHTML($html);
$mpdf->Output('dogovor_osago.pdf', 'I'); // Открыть PDF в браузере

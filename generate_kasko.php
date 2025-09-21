<?php
require_once __DIR__ . '/vendor/autoload.php';
use Mpdf\Mpdf;

// Получаем данные из формы
$model = $_POST['model'];
$year = intval($_POST['year']);
$price = floatval($_POST['price']);
$driver_age = intval($_POST['driver_age']);
$experience = intval($_POST['experience']);
$franchise = $_POST['franchise'] === 'yes' ? 'С франшизой' : 'Без франшизы';
$accidents = $_POST['accidents'] === 'yes' ? 'Были' : 'Не было';
$price_calc = floatval($_POST['price_calc']);

// Формируем HTML договора
$html = "
<head>
<title>Документ</title>
</head>
<h1>Договор КАСКО</h1>
<p>г. Тверь, «___» ________ 20__ г.</p>
<p>Страховая компания (далее – Страховщик) и Страхователь заключили настоящий договор КАСКО на следующих условиях:</p>

<h3>1. Данные страхователя</h3>
<p>Возраст водителя: <strong>$driver_age лет</strong></p>
<p>Стаж вождения: <strong>$experience лет</strong></p>
<p>Наличие аварий: <strong>$accidents</strong></p>

<h3>2. Данные автомобиля</h3>
<p>Марка/модель: <strong>$model</strong></p>
<p>Год выпуска: <strong>$year</strong></p>
<p>Стоимость автомобиля: <strong>".number_format($price,2,',',' ')." руб.</strong></p>
<p>Франшиза: <strong>$franchise</strong></p>

<h3>3. Стоимость договора</h3>
<p>Итоговая стоимость КАСКО составляет: <strong>".number_format($price_calc,2,',',' ')." руб.</strong></p>

<h3>4. Подписи сторон</h3>
<p>Страховщик: _________________________________________________________________</p>
<p>Страхователь: _______________________________________________________________</p>
";

// Создаем PDF
$mpdf = new Mpdf([
    'default_font' => 'dejavusans'
]);
$mpdf->WriteHTML($html);
$mpdf->Output('dogovor_kasko.pdf', 'I'); // Открыть PDF в браузере

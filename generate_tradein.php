<?php
require_once __DIR__ . '/vendor/autoload.php';
use Mpdf\Mpdf;

// Получаем данные из формы
$model = $_POST['model'];
$year = intval($_POST['year']);
$base_price = floatval($_POST['base_price']);
$mileage = intval($_POST['mileage']);
$condition = $_POST['condition'];
$final_price = floatval($_POST['final_price']);

// Красивое отображение состояния
$condition_labels = [
    'excellent' => 'Отличное',
    'good' => 'Хорошее',
    'satisfactory' => 'Удовлетворительное',
    'bad' => 'Плохое'
];
$condition_text = $condition_labels[$condition] ?? ucfirst($condition);

$current_year = date("Y");
$age = $current_year - $year;

// Формируем HTML-документ
$html = "
<head>
<title>Документ</title>
</head>
<h1 style='text-align:center;'>Оценка автомобиля по программе Trade-in</h1>
<p><strong>Дата: </strong>".date("d.m.Y")."</p>

<h3>Информация об автомобиле:</h3>
<ul>
    <li><strong>Модель:</strong> $model</li>
    <li><strong>Год выпуска:</strong> $year</li>
    <li><strong>Возраст:</strong> $age лет</li>
    <li><strong>Пробег:</strong> ".number_format($mileage, 0, ',', ' ')." км</li>
    <li><strong>Состояние:</strong> $condition_text</li>
</ul>

<h3>Финансовые данные:</h3>
<ul>
    <li><strong>Базовая стоимость нового авто:</strong> ".number_format($base_price, 2, ',', ' ')." руб.</li>
    <li><strong>Итоговая стоимость по Trade-in:</strong> <span style='font-size:16px; color:#d63031;'>".number_format($final_price, 2, ',', ' ')." руб.</span></li>
</ul>

<p>Данная оценка является предварительной и может отличаться от итоговой после технической диагностики автомобиля.</p>

<h3>Подписи сторон:</h3>
<p>Представитель дилера: _________________________________________________________</p>
<p>Клиент: _______________________________________________________________________</p>
";

// Создаем PDF
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('tradein_report.pdf', 'I'); // 'I' — открыть в браузере

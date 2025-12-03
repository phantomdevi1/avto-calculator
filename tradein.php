<?php
session_start();
require "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

/* =============================
   ОБРАБОТКА РЕЗУЛЬТАТА
============================= */

$result = '';

if (isset($_POST['calculate'])) {

    // Если выбрана другая марка — подставляем вручную введённую
    $brand = ($_POST['brand'] === "other") ? trim($_POST['other_brand'] ?? '') : trim($_POST['brand_name_hidden'] ?? '');

    // Если выбрана другая модель — подставляем вручную введённую
    $model = ($_POST['model'] === "other") ? trim($_POST['other_model'] ?? '') : trim($_POST['model'] ?? '');

    $year = intval($_POST['year'] ?? 0);
    $base_price = floatval(str_replace(' ', '', $_POST['base_price'] ?? 0));
    $mileage = intval($_POST['mileage'] ?? 0);
    $condition = $_POST['condition'] ?? 'good';

    $current_year = date("Y");
    $age = $current_year - $year;

    // Начинаем с базовой стоимости
    $final_price = $base_price;

    // Корректировка за возраст
    if ($age > 3) {
        $final_price -= $base_price * 0.05 * ($age - 3);
    }

    // Корректировка за пробег
    if ($mileage > 200000) {
        $final_price -= $base_price * 0.20;
    } elseif ($mileage > 100000) {
        $final_price -= $base_price * 0.10;
    }

    // Корректировка за состояние авто
    switch ($condition) {
        case 'excellent':
            $final_price *= 1.05;
            break;
        case 'satisfactory':
            $final_price -= $base_price * 0.10;
            break;
        case 'bad':
            $final_price -= $base_price * 0.25;
            break;
        default:
            // 'good' — без изменений
            break;
    }

    // Минимум 10% от новой цены
    if ($final_price < $base_price * 0.1) {
        $final_price = $base_price * 0.1;
    }

    /* =============== HTML результата =============== */

    $result = "
    <div class='result' id='result-block'>
        <h2>Оценка Trade-in</h2>
        <p>Марка: <strong>".htmlspecialchars($brand)."</strong></p>
        <p>Модель: <strong>".htmlspecialchars($model)."</strong></p>
        <p>Год выпуска: <strong>".htmlspecialchars($year)."</strong></p>
        <p>Базовая стоимость нового авто: <strong>".number_format($base_price, 2, ',', ' ')." руб.</strong></p>
        <p>Возраст автомобиля: <strong>".htmlspecialchars($age)." лет</strong></p>
        <p>Пробег: <strong>".number_format($mileage, 0, ',', ' ')." км</strong></p>
        <p>Состояние: <strong>".ucfirst(htmlspecialchars($condition))."</strong></p>
        <p><strong>Итоговая стоимость по программе Trade-in: ".number_format($final_price, 2, ',', ' ')." руб.</strong></p>

        <form method='post' action='generate_tradein.php' target='_blank'>
            <input type='hidden' name='brand' value='".htmlspecialchars($brand)."'>
            <input type='hidden' name='model' value='".htmlspecialchars($model)."'>
            <input type='hidden' name='year' value='".htmlspecialchars($year)."'>
            <input type='hidden' name='base_price' value='".htmlspecialchars($base_price)."'>
            <input type='hidden' name='mileage' value='".htmlspecialchars($mileage)."'>
            <input type='hidden' name='condition' value='".htmlspecialchars($condition)."'>
            <input type='hidden' name='final_price' value='".htmlspecialchars($final_price)."'>
            <button type='submit' class='pdf-button'>Сформировать PDF-оценку</button>
        </form>

        <button type='button' class='back-button' id='clear-btn'>← Назад</button>
    </div>
    ";
}

/* =============================
   ЗАГРУЗКА МАРОК ИЗ БД
============================= */

$brands = $conn->query("SELECT id, brand_name FROM car_brands ORDER BY brand_name ASC");
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

        <!-- Марка авто -->
        <label>Марка авто:</label>
        <select name="brand" id="brand_select" required>
            <option value="">Выберите марку...</option>

            <?php while ($row = $brands->fetch_assoc()): ?>
                <option value="<?= (int)$row['id'] ?>"><?= htmlspecialchars($row['brand_name']) ?></option>
            <?php endwhile; ?>

            <option value="other">Другая марка...</option>
        </select>

        <div id="other_brand_block" style="display:none;">
            <label>Введите марку:</label>
            <input type="text" name="other_brand" id="other_brand" placeholder="Напр. TANK">
        </div>

        <!-- скрыто передаём название выбранной марки -->
        <input type="hidden" name="brand_name_hidden" id="brand_name_hidden" value="">

        <!-- Модель авто -->
        <label>Модель авто:</label>
        <select name="model" id="model_select" required>
            <option value="">Сначала выберите марку...</option>
        </select>

        <div id="other_model_block" style="display:none;">
            <label>Введите модель:</label>
            <input type="text" name="other_model" id="other_model" placeholder="Напр. 300">
        </div>

        <label>Год выпуска:</label>
        <input type="number" name="year" min="1980" max="<?php echo date('Y'); ?>" required>

        <label>Базовая стоимость нового авто (руб.):</label>
        <input type="text" name="base_price" id="base_price" required>

        <label>Пробег (км):</label>
        <input type="number" name="mileage" id="mileage" required>

        <label>Состояние автомобиля:</label>
        <select name="condition">
            <option value="excellent">Отличное</option>
            <option value="good">Хорошее</option>
            <option value="satisfactory">Удовлетворительное</option>
            <option value="bad">Плохое</option>
        </select>

        <input type="submit" name="calculate" value="Рассчитать">
    </form>

    <?= $result ?>
</div>

<script>
// Форматирование чисел
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

document.getElementById('tradein-form').addEventListener('submit', function(e){
    // снимаем форматирование перед отправкой
    document.getElementById('base_price').value = unformatNumber(document.getElementById('base_price').value);
});

// ============================
// AJAX загрузка моделей (робастная обработка JSON)
// ============================

document.getElementById("brand_select").addEventListener("change", function() {
    const brandId = this.value;
    const modelSelect = document.getElementById("model_select");
    const otherBrandBlock = document.getElementById("other_brand_block");
    const otherModelBlock = document.getElementById("other_model_block");
    const brandNameHidden = document.getElementById("brand_name_hidden");

    // сохраняем текст марки в скрытое поле (для передачи на сервер)
    brandNameHidden.value = this.options[this.selectedIndex] ? this.options[this.selectedIndex].text : '';

    // Если "другая марка"
    if (brandId === "other") {
        otherBrandBlock.style.display = "block";
        otherModelBlock.style.display = "block";
        modelSelect.innerHTML = '<option value="other">Введите модель вручную</option>';
        return;
    }

    // сбрасываем блоки "другая"
    otherBrandBlock.style.display = "none";
    otherModelBlock.style.display = "none";

    if (!brandId) {
        modelSelect.innerHTML = '<option value="">Сначала выберите марку...</option>';
        return;
    }

    // Загружаем модели из БД
    fetch("get_models.php?brand_id=" + encodeURIComponent(brandId), { cache: "no-store" })
        .then(response => response.json())
        .then(data => {
            // допускаем 2 формата ответа:
            // 1) { "models": [ {model_name: "X"}, ... ] }
            // 2) [ {model_name: "X"}, ... ]
            let models = [];
            if (Array.isArray(data)) {
                models = data;
            } else if (data && Array.isArray(data.models)) {
                models = data.models;
            } else {
                // На всякий случай — попробуем понять, если вернулась просто объект-модель
                console.warn("Unexpected get_models response format:", data);
            }

            modelSelect.innerHTML = "";
            if (models.length === 0) {
                modelSelect.innerHTML = '<option value="">Модели не найдены</option><option value="other">Другая модель...</option>';
                return;
            }

            models.forEach(m => {
                // ожидаем поле model_name
                const name = m.model_name || m.name || m.model || '';
                modelSelect.innerHTML += `<option value="${escapeHtml(name)}">${escapeHtml(name)}</option>`;
            });

            modelSelect.innerHTML += '<option value="other">Другая модель...</option>';
        })
        .catch(err => {
            console.error("Ошибка при запросе моделей:", err);
            modelSelect.innerHTML = '<option value="">Ошибка загрузки моделей</option><option value="other">Другая модель...</option>';
        });
});

// Если выбрана "другая модель"
document.getElementById("model_select").addEventListener("change", function() {
    const otherModelBlock = document.getElementById("other_model_block");
    otherModelBlock.style.display = (this.value === "other") ? "block" : "none";
});

// Кнопка назад
document.addEventListener('click', function(e){
    if(e.target && e.target.id === 'clear-btn'){
        document.getElementById('tradein-form').reset();
        const resultBlock = document.getElementById('result-block');
        if(resultBlock) resultBlock.remove();
        // очищаем список моделей
        document.getElementById("model_select").innerHTML = '<option value="">Сначала выберите марку...</option>';
    }
});

// простая escape для вставки в option
function escapeHtml(text) {
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}
</script>

</body>
</html>

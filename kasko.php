<?php
session_start();
require "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$result = '';

if (isset($_POST['calculate'])) {

    // ---------- МАРКА ----------
    if (isset($_POST["brand"]) && $_POST["brand"] === "other") {
        $brand = trim($_POST["brand_custom"]);
    } else {
        $brand_id = intval($_POST["brand"] ?? 0);
        $brand_row = $conn->query("SELECT brand_name FROM car_brands WHERE id = $brand_id");
        $brand_row = $brand_row ? $brand_row->fetch_assoc() : null;
        $brand = $brand_row ? $brand_row['brand_name'] : 'Неизвестно';
    }

    // ---------- МОДЕЛЬ ----------
    if (isset($_POST["model"]) && $_POST["model"] === "other") {
        $model = trim($_POST["model_custom"]);
    } else {
        $model = $_POST["model"] ?? 'Неизвестно';
    }

    $year = intval($_POST['year'] ?? 0);
    $price = floatval(str_replace(' ', '', $_POST['price'] ?? 0));
    $driver_age = intval($_POST['driver_age'] ?? 0);
    $experience = intval($_POST['experience'] ?? 0);
    $franchise = floatval(str_replace(' ', '', $_POST['franchise'] ?? 0));
    $accidents = $_POST['accidents'] ?? 'no';

    // Ограничение франшизы
    if ($franchise < 0) $franchise = 0;
    if ($franchise > 100000) $franchise = 100000;

    // Базовая ставка
    $base_rate = 5.0;

    if ($driver_age < 25) $base_rate += 2.0;
    elseif ($driver_age > 60) $base_rate += 1.0;

    if ($experience < 3) $base_rate += 2.5;
    elseif ($experience > 10) $base_rate -= 0.5;

    if ($franchise > 0) {
        $base_rate -= min($franchise / 50000, 2.0);
    }

    if ($accidents === 'yes') {
        $base_rate += 1.5;
    }

    $price_calc = $price * $base_rate / 100;

    $result = "
    <div class='result' id='result-block'>
        <h2>Расчёт КАСКО</h2>

        <p>Марка: <strong>".htmlspecialchars($brand)."</strong></p>
        <p>Модель: <strong>".htmlspecialchars($model)."</strong></p>
        <p>Год выпуска: <strong>".htmlspecialchars($year)."</strong></p>
        <p>Стоимость автомобиля: <strong>".number_format($price, 2, ',', ' ')." руб.</strong></p>

        <p>Возраст водителя: <strong>".htmlspecialchars($driver_age)." лет</strong></p>
        <p>Стаж вождения: <strong>".htmlspecialchars($experience)." лет</strong></p>
        <p>Франшиза: <strong>".number_format($franchise, 0, ',', ' ')." руб.</strong></p>
        <p>Наличие аварий: <strong>".($accidents === 'yes' ? 'Были' : 'Не было')."</strong></p>

        <p><strong>Итоговая стоимость КАСКО: ".number_format($price_calc, 2, ',', ' ')." руб.</strong></p>

        <form method='post' action='generate_kasko.php' target='_blank'>
            <input type='hidden' name='brand' value='".htmlspecialchars($brand)."'>
            <input type='hidden' name='model' value='".htmlspecialchars($model)."'>
            <input type='hidden' name='year' value='".htmlspecialchars($year)."'>
            <input type='hidden' name='price' value='".htmlspecialchars($price)."'>
            <input type='hidden' name='driver_age' value='".htmlspecialchars($driver_age)."'>
            <input type='hidden' name='experience' value='".htmlspecialchars($experience)."'>
            <input type='hidden' name='franchise' value='".htmlspecialchars($franchise)."'>
            <input type='hidden' name='accidents' value='".htmlspecialchars($accidents)."'>
            <input type='hidden' name='price_calc' value='".htmlspecialchars($price_calc)."'>
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

        <!-- МАРКА -->
        <label>Марка авто:</label>
        <select name="brand" id="brand" required>
            <option value="">Выберите марку</option>
            <?php
            $brands = $conn->query("SELECT id, brand_name FROM car_brands ORDER BY brand_name ASC");
            while ($b = $brands->fetch_assoc()):
            ?>
                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['brand_name']) ?></option>
            <?php endwhile; ?>
            <option value="other">Своя марка</option>
        </select>

        <input type="text" name="brand_custom" id="brand_custom"
               placeholder="Введите марку вручную" style="display:none; margin-top:10px;">


        <!-- МОДЕЛЬ -->
        <label>Модель авто:</label>
        <select name="model" id="model" required>
            <option value="">Сначала выберите марку</option>
        </select>

        <input type="text" name="model_custom" id="model_custom"
               placeholder="Введите модель вручную" style="display:none; margin-top:10px;">


        <label>Год выпуска:</label>
        <input type="number" name="year" min="1980" max="<?php echo date('Y'); ?>" required>

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

        <input type="submit" name="calculate" value="Рассчитать">
    </form>

    <?= $result ?>
</div>

<script>
// ФОРМАТИРОВАНИЕ ЦЕНЫ
function formatNumber(input) {
    let v = input.value.replace(/\D/g, '');
    input.value = v.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
function unformatNumber(v) {
    return v.replace(/\s/g, '');
}
document.getElementById('price').addEventListener('input', function() {
    formatNumber(this);
});

// Перед отправкой убираем пробелы
document.getElementById('kasko-form').addEventListener('submit', function(){
    document.getElementById('price').value = unformatNumber(document.getElementById('price').value);
    // если пользователь ввёл свою марку/модель — сервер обработает brand/model === "other" и возьмёт поля brand_custom/model_custom
});

// ЗАПРОС МОДЕЛЕЙ ПО МАРКЕ (использует get_models.php)
document.getElementById("brand").addEventListener("change", function() {
    let id = this.value;
    let modelSelect = document.getElementById("model");
    let brandCustom = document.getElementById("brand_custom");
    let modelCustom = document.getElementById("model_custom");

    if (id === "other") {
        brandCustom.style.display = "block";
        modelSelect.innerHTML = "<option value='other'>Введите модель вручную</option>";
        modelCustom.style.display = "block";
        return;
    }

    brandCustom.style.display = "none";
    modelCustom.style.display = "none";

    fetch("get_models.php?brand_id=" + encodeURIComponent(id))
        .then(response => response.json())
        .then(data => {
            modelSelect.innerHTML = "<option value=''>Выберите модель</option>";
            data.models.forEach(item => {
                // item.model_name expected from get_models.php
                modelSelect.innerHTML += `<option value="${item.model_name}">${item.model_name}</option>`;
            });
            modelSelect.innerHTML += "<option value='other'>Своя модель</option>";
        })
        .catch(err => {
            modelSelect.innerHTML = "<option value=''>Ошибка загрузки</option>";
            console.error(err);
        });
});

document.getElementById("model").addEventListener("change", function() {
    if (this.value === "other") {
        document.getElementById("model_custom").style.display = "block";
    } else {
        document.getElementById("model_custom").style.display = "none";
    }
});

// Кнопка назад
document.addEventListener("click", function(e) {
    if (e.target && e.target.id === "clear-btn") {
        document.getElementById("kasko-form").reset();
        let rb = document.getElementById("result-block");
        if (rb) rb.remove();
    }
});
</script>

</body>
</html>

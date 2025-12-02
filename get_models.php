<?php
require "config.php";

$brand_id = intval($_GET['brand_id']);

$sql = "SELECT id, model_name FROM car_models WHERE brand_id = $brand_id ORDER BY model_name";
$res = $conn->query($sql);

$models = [];
while ($row = $res->fetch_assoc()) {
    $models[] = $row;
}

echo json_encode($models);
?>

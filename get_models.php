<?php
require "config.php";
header('Content-Type: application/json; charset=utf-8');

$brand_id = isset($_GET['brand_id']) ? intval($_GET['brand_id']) : 0;
if ($brand_id <= 0) {
    echo json_encode(['models' => []]);
    exit;
}

$stmt = $conn->prepare("SELECT id, model_name FROM car_models WHERE brand_id = ? ORDER BY model_name ASC");
$stmt->bind_param("i", $brand_id);
$stmt->execute();
$res = $stmt->get_result();

$models = [];
while ($row = $res->fetch_assoc()) {
    $models[] = $row;
}

echo json_encode(['models' => $models]);
exit;

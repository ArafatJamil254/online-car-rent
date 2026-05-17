<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../models/carModel.php';

$type = trim($_GET['type'] ?? '');

if ($type === '') {
    echo json_encode([
        "success" => false,
        "message" => "Category is required."
    ]);
    exit;
}

$result = task1GetCarsByCategory($type);
$cars = [];

while ($row = mysqli_fetch_assoc($result)) {
    $cars[] = $row;
}

echo json_encode([
    "success" => true,
    "cars" => $cars
]);
?>

<?php
session_start();
require_once('../models/carModel.php');

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$car_id     = $_GET['car_id'];
$start_date = $_GET['start_date'];
$end_date   = $_GET['end_date'];

if($car_id == "" || $start_date == "" || $end_date == ""){
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

$today = date('Y-m-d');
if($start_date < $today){
    echo json_encode(['error' => 'Start date cannot be in the past']);
    exit;
}

if($end_date <= $start_date){
    echo json_encode(['error' => 'End date must be after start date']);
    exit;
}

$result = getCarById($car_id);
$car = mysqli_fetch_assoc($result);

if(!$car){
    echo json_encode(['error' => 'Car not found']);
    exit;
}

$days       = (strtotime($end_date) - strtotime($start_date)) / 86400;
$total_cost = $car['price_per_day'] * $days;

echo json_encode(['total_cost' => $total_cost, 'days' => $days]);
?>
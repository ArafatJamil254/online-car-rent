<?php
session_start();

require_once('../models/orderModel.php');
require_once('../models/carModel.php');

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
    header('location: ../views/login.php');
    exit;
}

if(isset($_POST['order'])){

    $car_id     = $_REQUEST['car_id'];
    $start_date = $_REQUEST['start_date'];
    $end_date   = $_REQUEST['end_date'];
    $user_id    = $_SESSION['user_id'];

    if($car_id == "" || $start_date == "" || $end_date == ""){
        echo "All fields are required!";
        exit;
    }

    $today = date('Y-m-d');
    if($start_date < $today){
        echo "Start date cannot be in the past!";
        exit;
    }
    if($end_date <= $start_date){
        echo "End date must be after start date!";
        exit;
    }

    $result = getCarById($car_id);
    $car = mysqli_fetch_assoc($result);
    if(!$car){
        echo "Car not found!";
        exit;
    }

    $days       = (strtotime($end_date) - strtotime($start_date)) / 86400;
    $total_cost = $car['price_per_day'] * $days;

    $order = [
        'user_id'    => $user_id,
        'car_id'     => $car_id,
        'start_date' => $start_date,
        'end_date'   => $end_date,
        'total_cost' => $total_cost
    ];

    $order_id = placeOrder($order);

    if($order_id){
        header("location: ../views/invoice.php?order_id={$order_id}");
    }else{
        echo "Order failed! Try again.";
    }

}else{
    echo "invalid request!";
}
?>
<?php
require_once('db.php');

function getAllOrders($status='', $date=''){
    $con = getConnection();
    if($status != '' && $date != ''){
        $sql = "select orders.*, users.name as member_name, cars.name as car_name, cars.model from orders join users on orders.user_id=users.id join cars on orders.car_id=cars.id where orders.status=? and orders.start_date=? order by orders.id desc";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $status, $date);
    }else if($status != ''){
        $sql = "select orders.*, users.name as member_name, cars.name as car_name, cars.model from orders join users on orders.user_id=users.id join cars on orders.car_id=cars.id where orders.status=? order by orders.id desc";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $status);
    }else if($date != ''){
        $sql = "select orders.*, users.name as member_name, cars.name as car_name, cars.model from orders join users on orders.user_id=users.id join cars on orders.car_id=cars.id where orders.start_date=? order by orders.id desc";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $date);
    }else{
        $sql = "select orders.*, users.name as member_name, cars.name as car_name, cars.model from orders join users on orders.user_id=users.id join cars on orders.car_id=cars.id order by orders.id desc";
        $stmt = mysqli_prepare($con, $sql);
    }
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
?>
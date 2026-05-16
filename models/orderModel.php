<?php
require_once('db.php');
//task2-23-54253-3(get all orders)
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
//task2-23-54253-3(count orders)
function countOrders(){
    $con = getConnection();
    $result = mysqli_query($con, "select count(*) as total from orders");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}
?>
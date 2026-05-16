<?php
require_once __DIR__ . '/db.php';
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

function cancelOrder($order_id) {
    $con = getConnection();
    $sql = "UPDATE orders SET status='cancelled' WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    return mysqli_stmt_execute($stmt);
}

function confirmOrder($order_id, $payment_method) {
    $con = getConnection();
    $sql = "UPDATE orders SET status='confirmed', payment_method=? WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "si", $payment_method, $order_id);
    return mysqli_stmt_execute($stmt);
}

function getRentalHistoryByUser($user_id) {
    $con = getConnection();
    $sql = "SELECT orders.*, cars.name as car_name, cars.model, payments.payment_method
            FROM orders
            JOIN cars ON orders.car_id = cars.id
            LEFT JOIN payments ON payments.order_id = orders.id
            WHERE orders.user_id = ?
            ORDER BY orders.id DESC";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)) { $rows[] = $row; }
    return $rows;
}

function getOrderById($id) {
    $con = getConnection();
    $sql = "SELECT orders.*, cars.name as car_name, cars.model, payments.payment_method
            FROM orders
            JOIN cars ON orders.car_id = cars.id
            LEFT JOIN payments ON payments.order_id = orders.id
            WHERE orders.id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

function placeOrder($order) {
    $con = getConnection();
    $sql = "INSERT INTO orders(user_id, car_id, start_date, end_date, total_cost, status) VALUES(?,?,?,?,?,'pending')";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "iissd", $order['user_id'], $order['car_id'], $order['start_date'], $order['end_date'], $order['total_cost']);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($con);
}
//task2-23-54253-3(count orders)
function countOrders(){
    $con = getConnection();
    $result = mysqli_query($con, "select count(*) as total from orders");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}
?>
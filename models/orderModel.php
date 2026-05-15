<?php

    require_once(__DIR__. '\db.php');

    function placeOrder($order){
        $conn = getConnection();
        $sql = "INSERT INTO orders (user_id, car_id, start_date, end_date, total_cost, status, order_date)
                VALUES ('{$order['user_id']}', '{$order['car_id']}', '{$order['start_date']}',
                        '{$order['end_date']}', '{$order['total_cost']}', 'pending', NOW())";
        if(mysqli_query($conn, $sql)){
            return mysqli_insert_id($conn);
        }
        return false;
    }

    function getOrderById($order_id){
        $conn = getConnection();
        $sql = "SELECT o.*, c.name AS car_name, c.model, c.price_per_day, c.image_path
                FROM orders o
                JOIN cars c ON o.car_id = c.id
                WHERE o.id='{$order_id}'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == 1){
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    function cancelOrder($order_id){
        $conn = getConnection();
        $sql = "UPDATE orders SET status='cancelled' WHERE id='{$order_id}'";
        return mysqli_query($conn, $sql);
    }

    function confirmOrder($order_id, $payment_method){
        $conn = getConnection();
        $sql = "UPDATE orders SET status='connfirmed', payment_method='{$payment_method}' WHERE id='{$order_id}'";
        return mysqli_query($conn, $sql);
    }

    function getRentalHistoryByUser($user_id){
        $conn = getConnection();
        $sql = "SELECT o.*, c.name AS car_name, c.model
                FROM orders o
                JOIN cars c ON o.car_id = c.id
                WHERE o.user_id='{$user_id}'
                ORDER BY o.order_date DESC";
        $result = mysqli_query($conn, $sql);
        $orders = [];
        while($row = mysqli_fetch_assoc($result)){
            $orders[] = $row;
        }
        return $orders;
    }

?>
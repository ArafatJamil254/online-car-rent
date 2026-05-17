<?php
    session_start();
    require_once('../models/orderModel.php');

    header('Content-Type: application/json');

    if(!isset($_SESSION['user_id'])){
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }

    $order_id = $_POST['order_id'];
    $order = getOrderById($order_id);

    if(!$order || $order['user_id'] != $_SESSION['user_id']){
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    if($order['status'] != 'pending'){
        echo json_encode(['success' => false, 'message' => 'Order already processed']);
        exit;
    }

    $result = cancelOrder($order_id);

    if($result){
        echo json_encode(['success' => true, 'message' => 'Order cancelled']);
    }else{
        echo json_encode(['success' => false, 'message' => 'Cancel failed']);
    }
?>
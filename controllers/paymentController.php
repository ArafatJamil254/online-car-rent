<?php
    session_start();
    require_once('../models/orderModel.php');
    require_once('../models/paymentModel.php');

    if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
        header('location: ../view/login.php');
        exit;
    }

    if(isset($_POST['payment_submit'])){

        $order_id       = $_REQUEST['order_id'];
        $payment_method = $_REQUEST['payment_method'];
        $user_id        = $_SESSION['user_id'];

        if($order_id == "" || $payment_method == ""){
            echo "All fields required!";
            exit;
        }

        $order = getOrderById($order_id);

        if(!$order){
            echo "Order not found!";
            exit;
        }

        if($order['user_id'] != $user_id){
            echo "Unauthorized!";
            exit;
        }

        if($order['status'] != 'pending'){
            echo "Order already processed!";
            exit;
        }

        //  transaction id
        $transaction_id = strtoupper(uniqid('TXN'));

        $payment = [
            'order_id'       => $order_id,
            'amount'         => $order['total_cost'],
            'payment_method' => $payment_method,
            'transaction_id' => $transaction_id
        ];

        $pay_status = insertPayment($payment);

        if($pay_status){
            confirmOrder($order_id, $payment_method);
            header("location: ../views/payment_success.php?order_id={$order_id}");
        }else{
            echo "Payment failed! Try again.";
        }

    }else{
        echo "invalid request!";
    }

?>
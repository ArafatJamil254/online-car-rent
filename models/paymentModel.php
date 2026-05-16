<?php
require_once(__DIR__.'/db.php');

function insertPayment($payment)
{
    $con = getConnection();
    $sql = "INSERT INTO payments (order_id, amount, payment_method, transaction_id, payment_date)
                VALUES ('{$payment['order_id']}', '{$payment['amount']}', '{$payment['payment_method']}',
                        '{$payment['transaction_id']}', NOW())";
    return mysqli_query($con, $sql);
}

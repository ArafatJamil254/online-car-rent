<?php
    session_start();
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
        header('location: login.php');
        exit;
    }
    require_once('../models/orderModel.php');

    $order_id = $_GET['order_id'];
    $order    = getOrderById($order_id);

    if(!$order){
        echo "Order not found!";
        exit;
    }

    if($order['user_id'] != $_SESSION['user_id']){
        echo "Unauthorized!";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice</title>
    <link rel="stylesheet" href="../assets/styles.css"/>
</head>
<body>

    <nav>
        <a href="home.php">Home</a>
        <a href="rental_history.php">My Rentals</a>
        <a href="../controller/logout.php">Logout</a>
    </nav>

    <h2>Invoice</h2>

    <div class="invoice-box">
        <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
        <p><strong>Car:</strong> <?php echo $order['car_name']; ?> — <?php echo $order['model']; ?></p>
        <p><strong>Start Date:</strong> <?php echo $order['start_date']; ?></p>
        <p><strong>End Date:</strong> <?php echo $order['end_date']; ?></p>
        <p><strong>Total Cost:</strong> BDT <?php echo $order['total_cost']; ?></p>
        <p><strong>Status:</strong> <span class="status-pending"><?php echo strtoupper($order['status']); ?></span></p>
    </div>

    <br>

    <?php if($order['status'] == 'pending'){ ?>

        <button class="btn btn-red" onclick="cancelOrder(<?php echo $order['id']; ?>)">Cancel Order</button>
        &nbsp;
        <a class="btn btn-green" href="payment.php?order_id=<?php echo $order['id']; ?>">Finalize &amp; Pay Now</a>

        <p id="cancel_msg"></p>

    <?php }else{ ?>
        <p>This order has already been <strong><?php echo $order['status']; ?></strong>.</p>
    <?php } ?>

    <script src="../assets/invoice.js">
           
    </script>

</body>
</html>
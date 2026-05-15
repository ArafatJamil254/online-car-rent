<?php
    session_start();
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
        header('location: login.php');
        exit;
    }
    require_once('../models/orderModel.php');

    $user_id = $_SESSION['user_id'];
    $orders  = getRentalHistoryByUser($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Rental History</title>
    <link rel="stylesheet" href="../assets/styles.css"/>
</head>
<body>

    <nav>
        <a href="home.php">Home</a>
        <a href="rental_history.php">My Rentals</a>
        <a href="../controllers/logout.php">Logout</a>
    </nav>

    <h2>My Rental History</h2>
    <p>Welcome, <strong><?php echo $_SESSION['name']; ?></strong></p>

    <?php if(count($orders) == 0){ ?>
        <p>No rental history found.</p>
    <?php }else{ ?>

        <table>
            <tr>
                <th>#</th>
                <th>Car</th>
                <th>Model</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Cost (BDT)</th>
                <th>Status</th>
                <th>Payment</th>
            </tr>

            <?php $i = 1; foreach($orders as $order){ ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $order['car_name']; ?></td>
                <td><?php echo $order['model']; ?></td>
                <td><?php echo $order['start_date']; ?></td>
                <td><?php echo $order['end_date']; ?></td>
                <td><?php echo $order['total_cost']; ?></td>
                <td>
                    <?php if($order['status'] == 'confirmed'){ ?>
                        <span class="badge badge-confirmed">CONFIRMED</span>
                    <?php }elseif($order['status'] == 'cancelled'){ ?>
                        <span class="badge badge-cancelled">CANCELLED</span>
                    <?php }else{ ?>
                        <span class="badge badge-pending">PENDING</span>
                    <?php } ?>
                </td>
                <td><?php echo $order['payment_method'] != "" ? str_replace('_', ' ', strtoupper($order['payment_method'])) : "—"; ?></td>
            </tr>
            <?php } ?>

        </table>

    <?php } ?>

</body>
</html>
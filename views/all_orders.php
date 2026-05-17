<?php
    session_start();
    require_once __DIR__ . '/../models/orderModel.php';
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
        header('location: home.php');
        exit();
    }
    include('header.php');
    $status = $_GET['status']??'';
    $date = $_GET['date']??'';

    $orders = getAllOrders($status, $date);

?>
<div class="container">
    <h2>All Rent Order History</h2>
    <form method="get">
        Status:
        <select name="status">
            <option value="">All</option>
            <option value="pending" <?php if($status=='pending'){echo 'selected';}?>>Pending</option>
            <option value="confirmed" <?php if($status=='confirmed'){echo 'selected';}?>>Confirmed</option>
            <option value="cancelled" <?php if($status=='cancelled'){echo 'selected';}?>>Cancelled</option>
        </select>
        Date: <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>">
        <input type="submit" value="Filter">
    </form>
    <table>
    <tr>
        <th>Member</th>
        <th>Car</th>
        <th>Dates</th>
        <th>Total</th>
        <th>Status</th>
        <th>Payment</th>
    </tr>
    <?php while($order = mysqli_fetch_assoc($orders)){ ?>
    <tr>
    <td><?php echo htmlspecialchars($order['member_name']); ?></td>
    <td><?php echo htmlspecialchars($order['car_name']); ?> (<?php echo htmlspecialchars($order['model']); ?>)</td>
    <td><?php echo htmlspecialchars($order['start_date']); ?> to <?php echo htmlspecialchars($order['end_date']); ?></td>
    <td><?php echo htmlspecialchars($order['total_cost']); ?></td>
    <td><?php echo htmlspecialchars($order['status']); ?></td>
    <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
    </tr>
    <?php } ?>
    </table>
</div>
<?php include('footer.php'); ?>
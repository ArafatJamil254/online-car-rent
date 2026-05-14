<?php
    session_start();
    require_once('../model/orderModel.php');
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
        header('location: login.php');
    }
    $orders = getAllOrders();
?>

<h2>All Rent Order History</h2>
<form method="get">
    Status:
    <select name="status">
        <option value="">All</option>
        <?php foreach(['pending','confirmed','cancelled'] as $s){ ?>
        <option value="<?php echo $s; ?>" <?php if($status==$s){echo 'selected';}?>><?php echo $s; ?></option>
        <?php } ?>
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
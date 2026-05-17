<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member') {
    header('location: home.php');
    exit();
}
include('header.php');
require_once('../models/orderModel.php');

$order_id = $_GET['order_id'];
$order    = getOrderById($order_id);

if (!$order) {
    echo "Order not found!";
    exit;
}

if ($order['user_id'] != $_SESSION['user_id']) {
    echo "Unauthorized!";
    exit;
}

if ($order['status'] != 'pending') {
    echo "Order already processed!";
    exit;
}
?>

 

    <h2>Payment</h2>

    <div class="summary">
        <p><strong>Car:</strong> <?php echo $order['car_name']; ?> — <?php echo $order['model']; ?></p>
        <p><strong>Rental Period:</strong> <?php echo $order['start_date']; ?> to <?php echo $order['end_date']; ?></p>
        <p><strong>Total Cost:</strong> BDT <?php echo $order['total_cost']; ?></p>
    </div>

    <form method="post" action="../controllers/paymentController.php" onsubmit="return validatePayment()">

        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

        <h3>Select Payment Method:</h3>

        <label><input type="radio" name="payment_method" value="credit_card"> Credit Card</label>
        <label><input type="radio" name="payment_method" value="bkash"> bKash</label>
        <label><input type="radio" name="payment_method" value="nagad"> Nagad</label>
        <label><input type="radio" name="payment_method" value="bank_transfer"> Bank Transfer</label>
        <label><input type="radio" name="payment_method" value="cash_on_delivery"> Cash on Delivery</label>

        <span class="error" id="err_payment"></span>

        <br>
        <input type="submit" name="payment_submit" class="btn" value="Confirm Payment">
    </form>

    <script src="../assets/payment.js"></script>

<?php include('footer.php')?>
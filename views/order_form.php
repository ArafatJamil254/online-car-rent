<?php
    session_start();
    require_once(__DIR__.'/../models/carModel.php');
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
        header('location: login.php');
        exit;
    }

   if(!isset($_GET['car_id']) || empty($_GET['car_id'])){
    echo "Car not found! Missing car ID.";
    exit;
    }
    $car_id = (int)$_GET['car_id'];
    $car = getCarById($car_id);
    $car = getCarByIdAssoc($car_id);

    if(!$car){
        echo "Car not found!";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Place Order</title>
    <link rel="stylesheet" href="../assets/styles.css"/>
</head>
<body>

    <nav>
        <a href="home.php">Home</a>
        <a href="rental_history.php">My Rentals</a>
        <a href="../controllers/authController.php?action=logout">Logout</a>
    </nav>

    <h2>Book: <?php echo $car['name']; ?> (<?php echo $car['model']; ?>)</h2>
    <p>Price per day: <strong>BDT <?php echo $car['price_per_day']; ?></strong></p>

    <form method="post" action="../controllers/orderController.php" onsubmit="return validateOrder()">

        <input type="hidden" name="car_id" id="car_id" value="<?php echo $car['id']; ?>">

        <label>Start Date:</label>
        <input type="date" name="start_date" id="start_date" onchange="calcCost()">
        <span class="error" id="err_start"></span>

        <label>End Date:</label>
        <input type="date" name="end_date" id="end_date" onchange="calcCost()">
        <span class="error" id="err_end"></span>

        <div id="cost_display"></div>

        <span class="error" id="err_general"></span>

        <br>
        <input type="submit" name="order" class="btn" value="Place Order">
    </form>

    <script src="../assets/order.js"></script>

</body>
</html>
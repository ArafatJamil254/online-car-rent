<?php
session_start();
require_once(__DIR__ . '/../models/carModel.php');
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
    header('location: home.php');
    exit();
}
include('header.php');
?>

<?php if(!isset($_REQUEST['car_id']) || empty($_REQUEST['car_id'])){ ?>

    <div class="container">
        <h2>Available Cars</h2>
        <?php
        $cars = getAllCars();
        while($car = mysqli_fetch_assoc($cars)){
        ?>
        <div class="car-card">
            <?php if($car['image_path'] != ''){ ?>
                <img src="../<?php echo htmlspecialchars($car['image_path']); ?>" width="150" alt="Car Image">
            <?php } ?>
            <h3><?php echo htmlspecialchars($car['name']); ?> — <?php echo htmlspecialchars($car['model']); ?></h3>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($car['type']); ?></p>
            <p><strong>Price:</strong> BDT <?php echo htmlspecialchars($car['price_per_day']); ?> / day</p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($car['availability_status']); ?></p>
            <a class="btn" href="car_details.php?car_id=<?php echo $car['id']; ?>">View Details</a>
        </div>
        <?php } ?>
    </div>

<?php } else {

    $car_id = (int)$_REQUEST['car_id'];
     $result = getCarById($car_id);
 $car = mysqli_fetch_assoc($result);

    if(!$car){
        echo "<p>Car not found!</p>";
        include('footer.php');
        exit;
    }
?>

    <div class="container">
        <h2><?php echo htmlspecialchars($car['name']); ?> — <?php echo htmlspecialchars($car['model']); ?></h2>

        <?php if($car['image_path'] != ''){ ?>
            <img class="car-img" src="../<?php echo htmlspecialchars($car['image_path']); ?>" alt="Car Image">
        <?php } ?>

        <div class="car-info">
            <p><strong>Type:</strong> <?php echo htmlspecialchars($car['type']); ?></p>
            <p><strong>Price per day:</strong> BDT <?php echo htmlspecialchars($car['price_per_day']); ?></p>
            <p><strong>Availability:</strong> <?php echo htmlspecialchars($car['availability_status']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($car['description']); ?></p>
        </div>

        <?php if($car['availability_status'] == 'available'){ ?>
            <a class="btn" href="order_form.php?car_id=<?php echo $car['id']; ?>">Book This Car</a>
        <?php } else { ?>
            <p style="color:red;"><strong>This car is currently unavailable.</strong></p>
        <?php } ?>

        <br><br>
        <a href="car_details.php">← Back to all cars</a>
    </div>

<?php } ?>

<?php include('footer.php'); ?>
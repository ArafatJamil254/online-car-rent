<?php
session_start();
require_once(__DIR__ . '/../models/carModel.php');
if(!isset($_SESSION['user_id']) ){
    header('location: login.php');
    exit;
}
include('header.php');

$car_id = $_REQUEST['id'];
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

    <?php
        $image = !empty($car['image_path']) ? "../" . $car['image_path'] : "../assets/no-car.png";
    ?>
        <img class="car-img" src="<?php echo htmlspecialchars($image); ?>" alt="Car Image">

    <div class="car-info">
        <p><strong>Type:</strong> <?php echo htmlspecialchars($car['type']); ?></p>
        <p><strong>Price per day:</strong> BDT <?php echo htmlspecialchars($car['price_per_day']); ?></p>
        <p><strong>Availability:</strong> <?php echo htmlspecialchars($car['availability_status']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($car['description']); ?></p>
    </div>

    <?php
        if($_SESSION['role']=='member'){
            if($car['availability_status'] == 'available'){ ?>
                <a class="btn" href="order_form.php?car_id=<?php echo $car['id']; ?>">Book This Car</a>
    <?php    
            } else { ?>
                <p style="color:red;"><strong>This car is currently unavailable.</strong></p>
    <?php   }
        }
    ?>
    

    <br><br>
    <a href="browseCars.php">← Back to all cars</a>
</div>

<?php include('footer.php'); ?>
<?php
session_start();
require_once(__DIR__ . '/../models/carModel.php');

$car_id = 0;

if (isset($_GET['car_id']) && $_GET['car_id'] !== '') {
    $car_id = (int)$_GET['car_id'];
} elseif (isset($_GET['id']) && $_GET['id'] !== '') {
    // Backward compatible with older home/category links that used ?id=...
    $car_id = (int)$_GET['id'];
}

if ($car_id <= 0) {
    header('Location: browseCars.php');
    exit;
}

$result = getCarById($car_id);
$car = mysqli_fetch_assoc($result);

include('header.php');

if (!$car) {
    echo '<div class="container"><p>Car not found!</p><a href="browseCars.php">Back to cars</a></div>';
    include('footer.php');
    exit;
}

$image = !empty($car['image_path']) ? '../' . $car['image_path'] : '../assets/no-car.png';
$isMember = isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'member';
$isLoggedIn = isset($_SESSION['user_id']);
?>

<div class="container">
    <h2><?php echo htmlspecialchars($car['name']); ?> - <?php echo htmlspecialchars($car['model']); ?></h2>

    <img class="car-img" src="<?php echo htmlspecialchars($image); ?>" alt="Car Image">

    <div class="car-info">
        <p><strong>Type:</strong> <?php echo htmlspecialchars($car['type']); ?></p>
        <p><strong>Price per day:</strong> BDT <?php echo htmlspecialchars($car['price_per_day']); ?></p>
        <p><strong>Availability:</strong> <?php echo htmlspecialchars($car['availability_status']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($car['description']); ?></p>
    </div>

    <?php if ($car['availability_status'] === 'available') { ?>
        <?php if ($isMember) { ?>
            <a class="btn" href="order_form.php?car_id=<?php echo (int)$car['id']; ?>">Book This Car</a>
        <?php } elseif ($isLoggedIn) { ?>
            <p><strong>Booking is available for member accounts only.</strong></p>
        <?php } else { ?>
            <a class="btn" href="login.php">Login to Book This Car</a>
        <?php } ?>
    <?php } else { ?>
        <p style="color:red;"><strong>This car is currently unavailable.</strong></p>
    <?php } ?>

    <br><br>
    <a href="browseCars.php">Back to all cars</a>
</div>

<?php include('footer.php'); ?>

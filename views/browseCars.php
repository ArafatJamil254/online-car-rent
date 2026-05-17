<?php
session_start();
require_once(__DIR__ . '/../models/carModel.php');
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
    header('location: home.php');
    exit;
}
include('header.php');
?>

<div class="container">
    <h2>Available Cars</h2>
    <?php
    $cars = getAllCars();
    while($car = mysqli_fetch_assoc($cars)){
        $image = !empty($car['image_path']) ? "../" . $car['image_path'] : "../assets/no-car.png";
    ?>
    <div class="car-card">
        <img class="car-img" src="<?php echo htmlspecialchars($image); ?>" alt="Car Image">
        <h3><?php echo htmlspecialchars($car['name']); ?> — <?php echo htmlspecialchars($car['model']); ?></h3>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($car['type']); ?></p>
        <p><strong>Price:</strong> BDT <?php echo htmlspecialchars($car['price_per_day']); ?> / day</p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($car['availability_status']); ?></p>
        <a class="btn" href="car_details.php?id=<?php echo $car['id']; ?>">View Details</a>
    </div>
    <?php } ?>
</div>

<?php include('footer.php'); ?>
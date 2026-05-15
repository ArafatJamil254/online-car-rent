<?php
    session_start();
    require_once(__DIR__.'\..\models\carModel.php');
    // if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
    //     header('location: login.php');
    //     exit;
    // }

    if(!isset($_REQUEST['car_id']) || empty($_REQUEST['car_id'])){
    echo "Car not found! Missing car ID.";
    exit;
    }
    $car_id = (int)$_REQUEST['car_id'];
    $car = getCarById($car_id);

    if(!$car){
        echo "Car not found!";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Car Details</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

    <nav>
        <a href="home.php">Home</a>
        <a href="rental_history.php">My Rentals</a>
        <a href="../controller/logout.php">Logout</a>
    </nav>

    <h2><?php echo $car['name']; ?> — <?php echo $car['model']; ?></h2>

    <?php if($car['image_path'] != ""){ ?>
        <img class="car-img" src="../assets/cars?php echo $car['image_path']; ?>" alt="Car Image">
    <?php } ?>

    <div class="info">
        <p><strong>Type:</strong> <?php echo $car['type']; ?></p>
        <p><strong>Price per day:</strong> BDT <?php echo $car['price_per_day']; ?></p>
        <p><strong>Availability:</strong> <?php echo $car['availability_status']; ?></p>
        <p><strong>Description:</strong> <?php echo $car['description']; ?></p>
    </div>

    <?php if($car['availability_status'] == 'available'){ ?>
        <br>
        <a class="btn" href="order_form.php?car_id=<?php echo $car['id']; ?>">Book This Car</a>
    <?php }else{ ?>
        <p style="color:red;"><strong>This car is currently unavailable.</strong></p>
    <?php } ?>

</body>
</html>
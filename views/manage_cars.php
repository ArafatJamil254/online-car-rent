<?php
session_start();
require_once('../models/carModel.php');
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
        header('location: home.php');
        exit();
    }
include('header.php');
$cars = getAllCars(); 
?>
<div class="container">
    <h2>Manage Cars</h2>

    <?php if(isset($_GET['error'])){ echo "<p class='error'>".htmlspecialchars($_GET['error'])."</p>"; } ?>
    <?php if(isset($_GET['success'])){ echo "<p class='success'>".htmlspecialchars($_GET['success'])."</p>"; } ?>

    <a class="btn" href="car_form.php">Add New Car</a>

    <table>
    <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Model</th>
        <th>Type</th>
        <th>Price</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while($car = mysqli_fetch_assoc($cars)){ 
        if($img = $car['image_path']==''){
            $img = '../assets/no-car.png';
            } else{
                $img = '../' . $car['image_path'];
            } ?>
    <tr>
        <td><img src="<?php echo htmlspecialchars($img); ?>" width="80"></td>
        <td><?php echo htmlspecialchars($car['name']); ?></td>
        <td><?php echo htmlspecialchars($car['model']); ?></td>
        <td><?php echo htmlspecialchars($car['type']); ?></td>
        <td><?php echo htmlspecialchars($car['price_per_day']); ?></td>
        <td><?php echo htmlspecialchars($car['availability_status']); ?></td>
        <td>
            <a class="btn" href="car_form.php?id=<?php echo $car['id']; ?>">Edit</a>
            <a class="btn btn-danger" href="../controllers/carController.php?delete=<?php echo $car['id']; ?>" onclick="return confirm('Delete this car?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
    </table>
</div>
<?php include('footer.php'); ?>
<?php
    session_start();
    require_once('../model/carModel.php');
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
        header('location: login.php');
    }

    $id = $_GET['id'];
    $car = ['id'=>'',
            'name'=>'',
            'model'=>'',
            'type'=>'Private car',
            'price_per_day'=>'',
            'availability_status'=>'available',
            'image_path'=>'',
            'description'=>''];

    if($id != ''){
        $result = getCarById($id);
        if(mysqli_num_rows($result) == 1){
             $car = mysqli_fetch_assoc($result); 
        }
    }
?>

<h2><?php if($id=='') {echo 'Add Car';} else {echo 'Edit Car';} ?></h2>

<?php if(isset($_GET['error'])){ echo "<p class='error'>".htmlspecialchars($_GET['error'])."</p>"; } ?>

<form action="../controllers/carController.php" method="post" enctype="multipart/form-data" onsubmit="return carValidation()">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($car['id']); ?>">
    <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($car['image_path']); ?>">
    Name:<br>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($car['name']); ?>"><br>
    Model:<br>
    <input type="text" name="model" id="model" value="<?php echo htmlspecialchars($car['model']); ?>"><br>
    Type:<br>
    <select name="type">
        <option value="Private car" <?php if($car['type']=='Private car'){echo 'selected';}?>>Private car</option>
        <option value="Microbus" <?php if($car['type']=='Microbus'){echo 'selected';}?>>Microbus</option>
        <option value="Pick-up" <?php if($car['type']=='Pick-up'){echo 'selected';}?>>Pick-up</option>
        <option value="SUV" <?php if($car['type']=='SUV'){echo 'selected';}?>>SUV</option>
        <option value="Luxury car" <?php if($car['type']=='Luxury car'){echo 'selected';}?>>Luxury car</option>
    </select><br>
    Price Per Day:<br>
    <input type="number" step="0.01" name="price_per_day" id="price_per_day" value="<?php echo htmlspecialchars($car['price_per_day']); ?>"><br>
    Availability:<br>
    <select name="availability_status">
        <option value="available" <?php if($car['availability_status']=='available'){echo 'selected';}?>>available</option>
        <option value="unavailable" <?php if($car['availability_status']=='unavailable'){echo 'selected';}?>>unavailable</option>
    </select><br>
    Description:<br>
    <textarea name="description" id="description" rows="5"><?php echo htmlspecialchars($car['description']); ?></textarea><br>
    Image:<br><input type="file" name="image"><br>
    <?php if($car['image_path'] != ''){ ?><img src="../<?php echo htmlspecialchars($car['image_path']); ?>" width="120"><br><?php } ?>

    <input type="submit" name="save_car" value="Save Car">
</form>

<script src="../assets/ajax.js"></script>
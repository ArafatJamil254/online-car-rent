<?php
require_once('../models/carModel.php');
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
        header('location: ../view/login.php');
    }

if(isset($_POST['save_car'])){
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $model = trim($_POST['model']);
    $type = $_POST['type'];
    $price = $_POST['price_per_day'];
    $description = trim($_POST['description']);
    $status = $_POST['availability_status'];
    $old_image = $_POST['old_image'];
    $image = $old_image;
    $error = "";

    if($name == "" || $model == "" || $type == "" || $price == "" || $description == "" || $status == ""){
        $error = "All fields are required";
    }else if($price <= 0){
        $error = "Price must be positive";
    }
    
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
        $img = uploadImage($_FILES['image'], 'cars');
        if($img == 'type_error'){ $error = "Only JPG/PNG image allowed"; }
        else if($img == 'size_error'){ $error = "Image must be 2MB or less"; }
        else if($img != ''){ $image = $img; }
    }
    if($error != ""){
        header("location: ../views/car_form.php?id=$id&error=" . urlencode($error));
        exit;
    }
    $car = ['id'=>$id, 
            'name'=>$name, 
            'model'=>$model, 
            'type'=>$type, 
            'price_per_day'=>$price, 
            'availability_status'=>$status, 
            'image_path'=>$image, 
            'description'=>$description
        ];

    if($id == ''){
        addCar($car);
    }else{
        updateCar($car);
    }
    header("location: ../views/manage_cars.php?success=Car saved successfully");
}

function uploadImage($file, $folder){
    if(!isset($file) || $file['name'] == ''){
        return '';
    }
    $allowed = ['image/jpeg', 'image/png'];
    if(!in_array($file['type'], $allowed)){
        return 'type_error';
    }
    if($file['size'] > 2 * 1024 * 1024){
        return 'size_error';
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = time() . '_' . rand(1,999) . '.' . $ext;
    $target = '../public/uploads/' . $folder . '/' . $name;

    if(move_uploaded_file($file['tmp_name'], $target)){
        return 'public/uploads/' . $folder . '/' . $name;
    }
    return '';
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    if(carHasActiveOrders($id)){
        header("location: ../views/manage_cars.php?error=Can not delete. This car has active orders");
        exit;
    }
    $carResult = getCarById($id);
    if(mysqli_num_rows($carResult) == 1){
        $car = mysqli_fetch_assoc($carResult);
        if($car['image_path'] != '' && file_exists('../' . $car['image_path'])){
            unlink('../' . $car['image_path']);
        }
    }
    deleteCar($id);
    header("location: ../views/manage_cars.php?success=Car deleted");
}
?>    
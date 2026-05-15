<?php
require_once('db.php');
//task2-23-54253-3(get all cars)
function getCarById($id){
    $con = getConnection();
    $sql = "select * from cars where id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
//task2-23-54253-3(get all cars)
function getAllCars(){
    $con = getConnection();
    $sql="select * from cars order by id desc";
    return mysqli_query($con, $sql);
}
//task2-23-54253-3(add car)
function addCar($car){
    $con = getConnection();
    $sql = "insert into cars(name,model,type,price_per_day,availability_status,image_path,description) values(?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssdsss", $car['name'], $car['model'], $car['type'], $car['price_per_day'], $car['availability_status'], $car['image_path'], $car['description']);
    return mysqli_stmt_execute($stmt);
}
//task2-23-54253-3(update car)
function updateCar($car){
    $con = getConnection();
    $sql = "update cars set name=?, model=?, type=?, price_per_day=?, availability_status=?, image_path=?, description=? where id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssdsssi", $car['name'], $car['model'], $car['type'], $car['price_per_day'], $car['availability_status'], $car['image_path'], $car['description'], $car['id']);
    return mysqli_stmt_execute($stmt);
}
//task2-23-54253-3(check active orders before delete)
function carHasActiveOrders($id){
    $con = getConnection();
    $sql = "select id from orders where car_id=? and status in('pending','confirmed')";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}
//task2-23-54253-3(delete car)
function deleteCar($id){
    $con = getConnection();
    $sql = "delete from cars where id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}
//task2-23-54253-3(count cars)
function countCars(){
    $con = getConnection();
    $result = mysqli_query($con, "select count(*) as total from cars");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}
?>

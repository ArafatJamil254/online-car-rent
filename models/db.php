<?php
$host='localhost';
$user='root';
$password='';
$db='car_rent_project';
function getConnection(){
    global $host, $user, $password, $db;
    $con = mysqli_connect($host, $user, $password, $db);
    if(!$con){
        die("Database connection failed: " . mysqli_connect_error());
    }
    return $con;
}
?>

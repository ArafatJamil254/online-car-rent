<?php
function getConnection(){
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "online_car_rent";
    $con = mysqli_connect($host, $user, $pass, $db);
    if(!$con){
        die("Database connection failed: " . mysqli_connect_error());
    }
    return $con;
}
?>

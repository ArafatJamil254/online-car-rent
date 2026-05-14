

<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "online_car_rent";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) 
{
    die("Database connection failed.");
}
?>

<?php
require_once(__DIR__.'/../config/db.php');

function getCarById($car_id){
    $conn = getConnection();
    $id=(int)$car_id;
    $sql="Select * From cars Where id = '{$id}' ";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)==1){
        return mysqli_fetch_assoc($result);
    }
    return null;
}
?>
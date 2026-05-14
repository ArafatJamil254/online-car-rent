<?php
require_once('db.php');

function getCarById($id){
    $con = getConnection();
    $sql = "select * from cars where id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

?>

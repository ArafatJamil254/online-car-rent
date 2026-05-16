<?php
require_once __DIR__ . '/db.php';
//task2-23-54253-3(get all blogs)
function countBlogs(){
    $con = getConnection();
    $result = mysqli_query($con, "select count(*) as total from blogs");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}
?>
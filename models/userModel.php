<?php
require_once('db.php');
function getAllMembers(){
    $con = getConnection();
    $role = 'member';
    $sql = "select * from users where role=? order by id desc";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $role);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
function deleteMember($id){
    $con = getConnection();
    $role = 'member';
    $sql = "delete from users where id=? and role=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "is", $id, $role);
    return mysqli_stmt_execute($stmt);
}
?>
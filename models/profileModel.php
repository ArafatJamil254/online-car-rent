<?php
require_once __DIR__ . '/db.php';

// task1-23-53651-3(get logged in user by id)
function task1GetUserById($id){
    $con = getConnection();
    $sql = "select * from users where id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// task1-23-53651-3(check email except current user)
function task1EmailExistsForOtherUser($email, $id){
    $con = getConnection();
    $sql = "select id from users where email=? and id != ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "si", $email, $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

// task1-23-53651-3(update profile basic info)
function task1UpdateProfile($id, $name, $email, $address, $phone){
    $con = getConnection();
    $sql = "update users set name=?, email=?, address=?, phone=? where id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $address, $phone, $id);
    return mysqli_stmt_execute($stmt);
}

// task1-23-53651-3(update profile picture)
function task1UpdateProfilePicture($id, $profile_picture){
    $con = getConnection();
    $sql = "update users set profile_picture=? where id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "si", $profile_picture, $id);
    return mysqli_stmt_execute($stmt);
}

// task1-23-53651-3(change password)
function task1ChangePassword($id, $password_hash){
    $con = getConnection();
    $sql = "update users set password_hash=? where id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "si", $password_hash, $id);
    return mysqli_stmt_execute($stmt);
}
?>

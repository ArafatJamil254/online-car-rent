<?php
require_once('db.php');
function findUserByEmail($email) {
    $con = getConnection();

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function createUser($name, $email, $password_hash, $role, $address, $phone) {
    $con = getConnection();

    $sql = "INSERT INTO users (name, email, password_hash, role, address, phone)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssss",
        $name,
        $email,
        $password_hash,
        $role,
        $address,
        $phone
    );

    return mysqli_stmt_execute($stmt);
}

//task2-23-54253-3(get all members)
function getAllMembers(){
    $con = getConnection();
    $role = 'member';
    $sql = "select * from users where role=? order by id desc";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $role);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
//task2-23-54253-3(delete member)
function deleteMember($id){
    $con = getConnection();
    $role = 'member';
    $sql = "delete from users where id=? and role=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "is", $id, $role);
    return mysqli_stmt_execute($stmt);
}
//task2-23-54253-3(count members)
function countMembers(){
    $con = getConnection();
    $result = mysqli_query($con, "select count(*) as total from users where role='member'");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}
?>


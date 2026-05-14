<?php
require_once __DIR__ . '/../config/db.php';

function findUserByEmail($email) {
    global $conn;

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function createUser($name, $email, $password_hash, $role, $address, $phone) {
    global $conn;

    $sql = "INSERT INTO users (name, email, password_hash, role, address, phone)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

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
?>

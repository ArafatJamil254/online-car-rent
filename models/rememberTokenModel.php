<?php
require_once __DIR__ . '/db.php';

// task1-23-53651-3(save remember me token)
function task1SaveRememberToken($user_id, $token_hash, $expires_at){
    $con = getConnection();
    $sql = "insert into remember_tokens(user_id, token_hash, expires_at) values(?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $token_hash, $expires_at);
    return mysqli_stmt_execute($stmt);
}

// task1-23-53651-3(find user by remember me token)
function task1FindUserByRememberToken($token){
    $con = getConnection();

    $sql = "select users.*,
                   remember_tokens.token_hash
            from remember_tokens
            inner join users on remember_tokens.user_id = users.id
            where remember_tokens.expires_at > NOW()";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($token, $row['token_hash'])) {
            return $row;
        }
    }

    return null;
}

// task1-23-53651-3(delete remember tokens for logout)
function task1DeleteRememberTokensByUserId($user_id){
    $con = getConnection();
    $sql = "delete from remember_tokens where user_id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    return mysqli_stmt_execute($stmt);
}
?>

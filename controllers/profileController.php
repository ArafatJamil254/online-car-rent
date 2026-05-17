<?php
session_start();

require_once __DIR__ . '/../models/profileModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'update') {
    updateProfile();
} elseif ($action === 'changePassword') {
    changePassword();
} else {
    header("Location: ../views/profile.php");
    exit;
}

function updateProfile(){
    $id = $_SESSION['user_id'];

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '' || $email === '' || $address === '' || $phone === '') {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../views/profile.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
        header("Location: ../views/profile.php");
        exit;
    }

    if (task1EmailExistsForOtherUser($email, $id)) {
        $_SESSION['error'] = "Email already used by another account.";
        header("Location: ../views/profile.php");
        exit;
    }

    task1UpdateProfile($id, $name, $email, $address, $phone);
    $_SESSION['name'] = $name;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $file = $_FILES['profile_picture'];

        if ($file['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] = "Profile picture must be less than 2MB.";
            header("Location: ../views/profile.php");
            exit;
        }

        $mime = mime_content_type($file['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];

        if (!array_key_exists($mime, $allowed)) {
            $_SESSION['error'] = "Only JPG and PNG images are allowed.";
            header("Location: ../views/profile.php");
            exit;
        }

        $uploadDir = __DIR__ . '/../public/uploads/profile/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = "profile_" . $id . "_" . time() . "." . $allowed[$mime];
        $targetPath = $uploadDir . $fileName;
        $dbPath = "public/uploads/profile/" . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            task1UpdateProfilePicture($id, $dbPath);
        }
    }

    $_SESSION['success'] = "Profile updated successfully.";
    header("Location: ../views/profile.php");
    exit;
}

function changePassword(){
    $id = $_SESSION['user_id'];

    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');

    if ($currentPassword === '' || $newPassword === '') {
        $_SESSION['error'] = "Current and new password are required.";
        header("Location: ../views/profile.php");
        exit;
    }

    if (strlen($newPassword) < 8) {
        $_SESSION['error'] = "New password must be at least 8 characters.";
        header("Location: ../views/profile.php");
        exit;
    }

    $user = task1GetUserById($id);

    if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
        $_SESSION['error'] = "Current password is incorrect.";
        header("Location: ../views/profile.php");
        exit;
    }

    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    task1ChangePassword($id, $newHash);

    $_SESSION['success'] = "Password changed successfully.";
    header("Location: ../views/profile.php");
    exit;
}
?>

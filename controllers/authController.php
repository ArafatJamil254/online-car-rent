<?php
session_start();

require_once __DIR__ . '/../models/userModel.php';

$action = $_GET['action'] ?? '';

if ($action === 'register') {
    registerUser();
} elseif ($action === 'login') {
    loginUser();
} elseif ($action === 'logout') {
    logoutUser();
} else {
    header("Location: ../views/login.php");
    exit;
}

function registerUser() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/register.php");
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['role'] ?? '');

    if ($name === '' || $email === '' || $password === '' || $address === '' || $phone === '' || $role === '') {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../views/register.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
        header("Location: ../views/register.php");
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters.";
        header("Location: ../views/register.php");
        exit;
    }

    if ($role !== 'admin' && $role !== 'member') {
        $_SESSION['error'] = "Invalid role selected.";
        header("Location: ../views/register.php");
        exit;
    }

    if (findUserByEmail($email)) {
        $_SESSION['error'] = "Email already exists.";
        header("Location: ../views/register.php");
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $created = createUser($name, $email, $password_hash, $role, $address, $phone);

    if ($created) {
        $_SESSION['success'] = "Registration successful. Please login.";
        header("Location: ../views/login.php");
        exit;
    }

    $_SESSION['error'] = "Registration failed. Try again.";
    header("Location: ../views/register.php");
    exit;
}

function loginUser() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/login.php");
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $_SESSION['error'] = "Email and password are required.";
        header("Location: ../views/login.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
        header("Location: ../views/login.php");
        exit;
    }

    $user = findUserByEmail($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: ../views/login.php");
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    if (isset($_POST['remember'])) {
        setcookie("remember_user", $user['id'], time() + (86400 * 7), "/", "", false, true);
    }

    header("Location: ../views/home.php");
    exit;
}

function logoutUser() {
    session_destroy();
    setcookie("remember_user", "", time() - 3600, "/", "", false, true);

    header("Location: ../views/login.php");
    exit;
}
?>

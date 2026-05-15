<?php
session_start();

$error = $_SESSION['error'] ?? "";
$success = $_SESSION['success'] ?? "";

unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Online Car Rent</title>
    <link rel="stylesheet" href="../assets/auth.css">
</head>
<body>

<div class="auth-box">
    <h2>Create Account</h2>

    <div id="clientError" class="message error" style="display:none;"></div>

    <?php if (!empty($error)): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form id="registerForm" action="../controllers/authController.php?action=register" method="POST">
        <label>Name</label>
        <input type="text" name="name" placeholder="Enter your name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Minimum 8 characters" required>

        <label>Address</label>
        <input type="text" name="address" placeholder="Enter your address" required>

        <label>Phone</label>
        <input type="text" name="phone" placeholder="Enter your phone number" required>

        <label>Role</label>
        <select name="role" required>
            <option value="">Select role</option>
            <option value="member">Member</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">Register</button>
    </form>

    <div class="bottom-text">
        Already have an account?
        <a href="login.php">Login</a>
    </div>
</div>

<script src="../assets/ajax.js"></script>
</body>
</html>

<?php
session_start();
include('header.php');

$error = $_SESSION['error'] ?? "";
$success = $_SESSION['success'] ?? "";

unset($_SESSION['error'], $_SESSION['success']);
?>

<div class="auth-box">
    <h2>Login</h2>

    <div id="clientError" class="message error" style="display:none;"></div>

    <?php if (!empty($error)): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form id="loginForm" action="../controllers/authController.php?action=login" method="POST">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <div class="remember">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me</label>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="bottom-text">
        Don't have an account?
        <a href="register.php">Register</a>
    </div>
</div>

<script src="../assets/ajax.js"></script>
<?php include('footer.php'); ?>

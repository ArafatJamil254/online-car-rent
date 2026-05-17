<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

require_once __DIR__ . '/../models/profileModel.php';

$user = task1GetUserById($_SESSION['user_id']);

$error = $_SESSION['error'] ?? "";
$success = $_SESSION['success'] ?? "";

unset($_SESSION['error'], $_SESSION['success']);

$profileImage = "../assets/no-car.png";

if (!empty($user['profile_picture'])) {
    $profileImage = "../" . $user['profile_picture'];
}

include('header.php');
?>

<div class="container">
    <div class="section-box">
        <h2>My Profile</h2>

        <?php if (!empty($error)) { ?>
            <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <?php if (!empty($success)) { ?>
            <p class="success-msg"><?php echo htmlspecialchars($success); ?></p>
        <?php } ?>

        <div class="profile-box">
            <img class="profile-img" src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Picture">

            <form action="../controllers/profileController.php?action=update" method="POST" enctype="multipart/form-data">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

                <label>Profile Picture</label>
                <input type="file" name="profile_picture" accept="image/jpeg,image/png">

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>

    <div class="section-box">
        <h2>Change Password</h2>

        <form action="../controllers/profileController.php?action=changePassword" method="POST">
            <label>Current Password</label>
            <input type="password" name="current_password" required>

            <label>New Password</label>
            <input type="password" name="new_password" minlength="8" required>

            <button type="submit">Change Password</button>
        </form>
    </div>
</div>

<?php include('footer.php'); ?>

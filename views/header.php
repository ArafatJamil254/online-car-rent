<!DOCTYPE html>
<html>
<head>
    <title>Online Car Rent</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="header">
    <h2 style="color:aliceblue">Online Car Rent</h2>
    <div class="nav">
        <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'member'){ ?>

            <a href="home.php">Home</a>
            <a href="car_details.php">Browse Cars</a>
            <a href="rental_history.php">My Rentals</a>
            <a href="profile.php">Profile</a>
            <a href="blog.php">Blog</a>
            <a href="../controllers/authController.php?action=logout">Logout</a>

        <?php } elseif(isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'){ ?>

            <a href="home.php">Home</a>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_cars.php">Manage Cars</a>
            <a href="members.php">Members</a>
            <a href="all_orders.php">All Orders</a>
            <a href="profile.php">Profile</a>
            <a href="blog.php">Blog</a>
            <a href="../controllers/authController.php?action=logout">Logout</a>

        <?php } else { ?>

            <a href="login.php">Login</a>
            <a href="register.php">Register</a>

        <?php } ?>
    </div>
</div>
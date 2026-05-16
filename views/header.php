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
        <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="home.php">Home</a>
            <a href="car_list.php">Cars</a>
            <a href="blog.php">Blog</a>
            <a href="profile.php">Profile</a>
            <?php if($_SESSION['role'] == 'admin'){ ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
                <a href="manage_cars.php">Manage Cars</a>
                <a href="members.php">Members</a>
                <a href="all_orders.php">All Orders</a>
            <?php } ?>
            <a href="../controllers/authController.php?action=logout">Logout</a>
        <?php } else { ?>
            <a href="login.php">Login</a>
            <a href="register.php">Registration</a>
            <a href="car_list.php">Cars</a>
            <a href="blog.php">Blog</a>
        <?php } ?>
    </div>
</div>
<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("location: login.php");
        exit;
    }
    include('header.php');
?>
<div class="container">
    <h2>Welcome to Online Car Rent</h2>
    <p>Your one-stop solution for all your car rental needs. Browse our extensive collection of vehicles, read our latest blog posts, and manage your profile with ease. Happy driving!</p>
</div>
<?php include('footer.php'); ?>
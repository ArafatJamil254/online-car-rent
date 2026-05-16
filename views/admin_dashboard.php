<?php
    session_start();
    require_once('../model/userModel.php'); 
    require_once('../model/carModel.php'); 
    require_once('../model/orderModel.php'); 
    require_once('../model/blogModel.php');
    
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
        header('location: login.php');
    }
?>
<h2>Admin Dashboard</h2>
<div class="card"><h3>Total Cars</h3><p><?php echo countCars(); ?></p></div>
<div class="card"><h3>Total Members</h3><p><?php echo countMembers(); ?></p></div>
<div class="card"><h3>Total Orders</h3><p><?php echo countOrders(); ?></p></div>
<div class="card"><h3>Total Blog Posts</h3><p><?php echo countBlogs(); ?></p></div>
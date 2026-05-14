<?php
    session_start();
    require_once('../model/userModel.php');
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
        header('location: login.php');
    }
?>
<h2>Admin Dashboard</h2>
<div class="card"><h3>Total Cars</h3><p></p></div>
<div class="card"><h3>Total Members</h3><p></p></div>
<div class="card"><h3>Total Orders</h3><p></p></div>
<div class="card"><h3>Total Blog Posts</h3><p></p></div>
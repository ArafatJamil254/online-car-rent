<?php
session_start();
    require_once __DIR__ . '/../models/userModel.php';
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
        header('location: login.php');
    }
    include('header.php');
    $members = getAllMembers();
?>
<div class="container">
    <h2>All Members</h2>
    <?php if(isset($_GET['success'])){ echo "<p class='success'>".htmlspecialchars($_GET['success'])."</p>"; } ?>
    <table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Action</th>
    </tr>

    <?php while($member = mysqli_fetch_assoc($members)){ ?>
    <tr id="memberRow<?php echo $member['id']; ?>">
    <td><?php echo htmlspecialchars($member['name']); ?></td>
    <td><?php echo htmlspecialchars($member['email']); ?></td>
    <td><?php echo htmlspecialchars($member['phone']); ?></td>
    <td><?php echo htmlspecialchars($member['address']); ?></td>
    <td><button class="btn-danger" onclick="deleteMemberAjax(<?php echo $member['id']; ?>)">Delete</button></td>
    </tr>
    <?php } ?>
    </table>
    <script src="../assets/ajax.js"></script>
</div>
<?php include('footer.php'); ?>
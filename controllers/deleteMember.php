<?php
session_start();
require_once('../models/userModel.php');
header('Content-Type: application/json');

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo json_encode(['status'=>'error','message'=>'Admin only']);
    exit;
}
$id = $_POST['id'];
if($id == ''){
    echo json_encode(['status'=>'error','message'=>'ID required']);
    exit;
}
if(deleteMember($id)){
    echo json_encode(['status'=>'success','message'=>'Member deleted']);
}else{
    echo json_encode(['status'=>'error','message'=>'Delete failed']);
}
?>
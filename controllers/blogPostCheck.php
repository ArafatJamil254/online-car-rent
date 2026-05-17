<?php
 session_start();
    require_once('../models/blogModel.php');

    // Helper: detect if request is AJAX
    function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    // Helper: return JSON and exit
    function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Try to read JSON body (AJAX sends JSON)
        $rawInput  = file_get_contents('php://input');
        $jsonData  = json_decode($rawInput, true);
        $isAjax    = ($jsonData !== null && isset($jsonData['action']));

        if($isAjax && $jsonData['action'] == 'add'){

            // Check login
            if(!isset($_SESSION['user_id'])){
                jsonResponse(['success' => false, 'message' => 'Not logged in']);
            }

            $title   = isset($jsonData['title'])   ? trim($jsonData['title'])   : '';
            $content = isset($jsonData['content']) ? trim($jsonData['content']) : '';
            $user_id = $_SESSION['user_id'];

            // Validate
            if($title == '' || $content == ''){
                jsonResponse(['success' => false, 'message' => 'Title and content cannot be empty']);
            }
            if(strlen($title) > 255){
                jsonResponse(['success' => false, 'message' => 'Title is too long (max 255 characters)']);
            }
            if(strlen($content) > 10000){
                jsonResponse(['success' => false, 'message' => 'Content is too long (max 10000 characters)']);
            }

            // Save to DB
            $status = addBlog($user_id, $title, $content);

            if($status){
                $blogs = getAllBlogs();
                jsonResponse(['success' => true, 'message' => 'Blog posted!', 'blogs' => $blogs]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Database error. Could not save blog.']);
            }
        }

        if($isAjax && $jsonData['action'] == 'delete'){

            // Check login
            if(!isset($_SESSION['user_id'])){
                jsonResponse(['success' => false, 'message' => 'Not logged in']);
            }

            $blog_id = isset($jsonData['blog_id']) ? intval($jsonData['blog_id']) : 0;
            $user_id = $_SESSION['user_id'];
            $role    = isset($_SESSION['role']) ? $_SESSION['role'] : 'member';

            if($blog_id <= 0){
                jsonResponse(['success' => false, 'message' => 'Invalid blog ID']);
            }

            // Delete (model checks permission by role)
            $status = deleteBlog($blog_id, $user_id, $role);

            if($status){
                jsonResponse(['success' => true, 'message' => 'Blog deleted successfully']);
            } else {
                jsonResponse(['success' => false, 'message' => 'Could not delete. You may not own this post.']);
            }
        }

        if(isset($_POST['blog_submit'])){

            // Check login
            if(!isset($_SESSION['user_id'])){
                header('location: ../views/blog.php?error=auth');
                exit();
            }

            // CSRF token validation
            if(!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
               $_POST['csrf_token'] !== $_SESSION['csrf_token']){
                header('location: ../view/blog.php?error=csrf');
                exit();
            }

            $title   = trim($_POST['title']);
            $content = trim($_POST['content']);
            $user_id = $_SESSION['user_id'];

            // Server-side validation
            if($title == "" || $content == ""){
                header('location: ../views/blog.php?error=empty');
                exit();
            }
            if(strlen($title) > 255){
                header('location: ../views/blog.php?error=title_long');
                exit();
            }
            if(strlen($content) > 10000){
                header('location: ../views/blog.php?error=content_long');
                exit();
            }

            $status = addBlog($user_id, $title, $content);

            if($status){
                header('location: ../views/blog.php?success=1');
            } else {
                header('location: ../views/blog.php?error=db');
            }
            exit();
        }
    }


    // If nothing matched, just go to blog page
    header('location: ../views/blog.php');
    exit();

?>

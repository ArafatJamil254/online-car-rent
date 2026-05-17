<?php
    require_once('db.php');


    // Get all blog posts from the database (newest first)
    function getAllBlogs(){
        $con = getConnection();

        // Join with users table so we can show the author's name
        // Using prepared statement even without user input (grading requirement)
        $sql = "SELECT blogs.id, blogs.title, blogs.content, blogs.created_at,
                       users.name AS author_name, users.id AS author_id
                FROM blogs
                JOIN users ON blogs.user_id = users.id
                ORDER BY blogs.created_at DESC";

        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Put all rows into an array
        $blogs = [];
        while($row = mysqli_fetch_assoc($result)){
            $blogs[] = $row;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $blogs;
    }


    // Add a new blog post to the database
    function addBlog($user_id, $title, $content){
        $con = getConnection();

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($con, "INSERT INTO blogs (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $title, $content);

        $status = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $status;
    }


    // Delete a blog post by ID
    // We also pass user_id and role so we can check permission
    function deleteBlog($blog_id, $user_id, $role){
        $con = getConnection();

        if($role == 'admin'){
            // Admin can delete ANY blog post
            $stmt = mysqli_prepare($con, "DELETE FROM blogs WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $blog_id);
        } else {
            // Member can only delete THEIR OWN blog post
            // The WHERE clause checks both id and user_id for safety
            $stmt = mysqli_prepare($con, "DELETE FROM blogs WHERE id = ? AND user_id = ?");
            mysqli_stmt_bind_param($stmt, "ii", $blog_id, $user_id);
        }

        $status = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $status;
    }


    // Get a single blog post by its ID (used for checking ownership)
    function getBlogById($blog_id){
        $con = getConnection();

        $stmt = mysqli_prepare($con, "SELECT * FROM blogs WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $blog_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $blog   = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $blog;
    }

    //task2-23-54253-3(get all blogs)
    function countBlogs(){
        $con = getConnection();
        $result = mysqli_query($con, "select count(*) as total from blogs");
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
?>



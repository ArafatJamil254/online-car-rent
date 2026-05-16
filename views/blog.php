<?php
    // Start session to check login status
    session_start();

    // If user is not logged in, send them to login page
    if(!isset($_SESSION['user_id'])){
        header('location: login.php');
        exit();
    }

    // Get current user info from session
    $current_user_id = $_SESSION['user_id'];
    $current_role    = $_SESSION['role'];   // 'admin' or 'member'
    $current_name    = $_SESSION['name'];

    // Generate CSRF token for form security
    if(empty($_SESSION['csrf_token'])){
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf_token = $_SESSION['csrf_token'];

    // Include the blog model to fetch all blog posts
    require_once('../model/blogModel.php');
    $blogs = getAllBlogs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Page - Car Rent</title>
    <!-- Shared styles (reset, navbar, container, alerts) -->
    <link rel="stylesheet" href="../assets/css/shared.css">
    <!-- Blog-specific styles (form, cards, delete button, etc.) -->
    <link rel="stylesheet" href="../assets/css/blog.css">
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <span class="brand">Car Rent</span>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="blog.php" class="active">Blog</a>
            <?php if($current_role == 'admin'): ?>
                <a href="admin_dashboard.php">Admin Panel</a>
            <?php else: ?>
                <a href="profile.php">My Orders</a>
            <?php endif; ?>
            <a href="../controller/logout.php">Logout (<?php echo htmlspecialchars($current_name, ENT_QUOTES, 'UTF-8'); ?>)</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>Blog Page</h1>

        <!-- Show success or error message based on URL parameter -->
        <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">Your blog post was submitted successfully!</div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <?php if($_GET['error'] == 'empty'): ?>
                <div class="alert alert-error">Title and content cannot be empty.</div>
            <?php elseif($_GET['error'] == 'title_long'): ?>
                <div class="alert alert-error">Title is too long (max 255 characters).</div>
            <?php elseif($_GET['error'] == 'content_long'): ?>
                <div class="alert alert-error">Content is too long (max 10000 characters).</div>
            <?php elseif($_GET['error'] == 'auth'): ?>
                <div class="alert alert-error">You must be logged in to perform this action.</div>
            <?php else: ?>
                <div class="alert alert-error">Something went wrong. Please try again.</div>
            <?php endif; ?>
        <?php endif; ?>


        <!-- ======= POST A BLOG FORM ======= -->
        <div class="post-form-box">
            <h2>Share Your Experience</h2>

            <!-- Form has both AJAX handler (via JS) and fallback action (via controller) -->
            <form id="blogForm" action="../controller/blogPostCheck.php" method="POST">
                <!-- CSRF Token for security -->
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <input type="text" id="blogTitle" name="title" placeholder="Blog Title" maxlength="255" />
                <div class="field-error" id="titleError">Please enter a title.</div>

                <textarea id="blogContent" name="content" placeholder="Write your experience here..." maxlength="10000"></textarea>
                <div class="field-error" id="contentError">Please enter some content.</div>

                <button type="submit" name="blog_submit">Post Blog</button>
            </form>

            <!-- This message appears while AJAX is running -->
            <div id="loading-msg">Posting...</div>
            <div id="form-feedback"></div>
        </div>


        <!-- ======= ALL BLOG POSTS ======= -->
        <h2 class="section-heading">All Blog Posts</h2>

        <!-- Blog cards will be shown here. PHP loads them first, AJAX updates them. -->
        <div id="blogList">
            <?php if(count($blogs) == 0): ?>
                <div class="no-blogs">No blog posts yet. Be the first to share your experience!</div>
            <?php else: ?>
                <?php foreach($blogs as $blog): ?>
                    <div class="blog-card" id="blog-<?php echo $blog['id']; ?>">
                        <h3><?php echo htmlspecialchars($blog['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <div class="meta">
                            By <strong><?php echo htmlspecialchars($blog['author_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            &nbsp;|&nbsp;
                            <?php echo date('d M Y, h:i A', strtotime($blog['created_at'])); ?>
                        </div>
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($blog['content'], ENT_QUOTES, 'UTF-8')); ?>
                        </div>

                        <!-- Show delete button only if: admin, OR the post belongs to current user -->
                        <?php if($current_role == 'admin' || $blog['author_id'] == $current_user_id): ?>
                            <div class="card-footer">
                                <button class="btn-delete"
                                        onclick="deleteBlog(<?php echo $blog['id']; ?>)">
                                    Delete
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div><!-- end .container -->


    <!-- ======= JAVASCRIPT ======= -->
    <!-- Inject PHP session variables into JS scope before loading the external script -->
    <script>
        var currentUserId = <?php echo intval($current_user_id); ?>;
        var currentRole   = '<?php echo htmlspecialchars($current_role, ENT_QUOTES, "UTF-8"); ?>';
    </script>
    <!-- External JS file for blog form submission, deletion, and rendering -->
    <script src="../assets/js/blog.js"></script>

</body>
</html>

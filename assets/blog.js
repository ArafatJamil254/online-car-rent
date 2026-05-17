/**
 * blog.js - Client-side JavaScript for Task 4 Blog Feature
 * Handles: AJAX form submission, AJAX delete, blog list refresh, JS validation
 *
 * All AJAX calls go to controller/blogPostCheck.php (no api/ folder needed).
 * Uses POST with JSON body containing an "action" parameter.
 */

document.addEventListener('DOMContentLoaded', function () {

    // --- References to DOM elements ---
    const blogForm      = document.getElementById('blogForm');
    const titleInput    = document.getElementById('blogTitle');
    const contentInput  = document.getElementById('blogContent');
    const titleError    = document.getElementById('titleError');
    const contentError  = document.getElementById('contentError');
    const loadingMsg    = document.getElementById('loading-msg');
    const formFeedback  = document.getElementById('form-feedback');
    const blogList      = document.getElementById('blogList');

    // Session info injected by PHP
    // var currentUserId and currentRole are set in blog.php before this script loads

    // Controller URL - same file handles both regular form and AJAX
    const CONTROLLER_URL = '../controllers/blogPostCheck.php';


    // ===================================================
    //  JS VALIDATION - Blog Post Form
    // ===================================================

    function validateBlogForm() {
        let isValid = true;

        // Reset errors
        titleError.style.display   = 'none';
        contentError.style.display = 'none';

        // Title validation
        const title = titleInput.value.trim();
        if (title === '') {
            titleError.style.display = 'block';
            titleError.textContent   = 'Please enter a title.';
            isValid = false;
        } else if (title.length > 255) {
            titleError.style.display = 'block';
            titleError.textContent   = 'Title is too long (max 255 characters).';
            isValid = false;
        }

        // Content validation
        const content = contentInput.value.trim();
        if (content === '') {
            contentError.style.display = 'block';
            contentError.textContent   = 'Please enter some content.';
            isValid = false;
        } else if (content.length > 10000) {
            contentError.style.display = 'block';
            contentError.textContent   = 'Content is too long (max 10000 characters).';
            isValid = false;
        }

        return isValid;
    }

    // Live validation on input
    titleInput.addEventListener('input', function () {
        if (this.value.trim() !== '') {
            titleError.style.display = 'none';
        }
    });

    contentInput.addEventListener('input', function () {
        if (this.value.trim() !== '') {
            contentError.style.display = 'none';
        }
    });


    // ===================================================
    //  AJAX - Submit Blog Post (POST with action=add)
    // ===================================================

    blogForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        // JS validation first
        if (!validateBlogForm()) {
            return;
        }

        // Show loading indicator
        loadingMsg.style.display = 'block';
        formFeedback.innerHTML   = '';

        // Prepare data with action parameter
        const data = {
            action:  'add',
            title:   titleInput.value.trim(),
            content: contentInput.value.trim()
        };

        // Send AJAX POST request to the controller
        fetch(CONTROLLER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Server returned status ' + response.status);
            }
            return response.json();
        })
        .then(function (result) {
            loadingMsg.style.display = 'none';

            if (result.success) {
                // Clear the form
                titleInput.value   = '';
                contentInput.value = '';

                // Show success message
                formFeedback.innerHTML = '<div class="alert alert-success">Your blog post was submitted successfully!</div>';

                // Refresh the blog list without page reload
                if (result.blogs) {
                    renderBlogList(result.blogs);
                }

                // Auto-hide success message after 4 seconds
                setTimeout(function () {
                    formFeedback.innerHTML = '';
                }, 4000);
            } else {
                formFeedback.innerHTML = '<div class="alert alert-error">' + escapeHtml(result.message) + '</div>';
            }
        })
        .catch(function (error) {
            loadingMsg.style.display = 'none';
            formFeedback.innerHTML   = '<div class="alert alert-error">Network error. Please try again.</div>';
            console.error('AJAX Error:', error);
        });
    });


    // ===================================================
    //  AJAX - Delete Blog Post (POST with action=delete)
    // ===================================================

    /**
     * Delete a blog post by ID via AJAX.
     * Called from onclick in the delete button.
     */
    window.deleteBlog = function (blogId) {
        // Confirm before deleting
        if (!confirm('Are you sure you want to delete this blog post?')) {
            return;
        }

        // Prepare data with action parameter
        const data = {
            action:  'delete',
            blog_id: blogId
        };

        // Send AJAX POST request to the controller
        fetch(CONTROLLER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Server returned status ' + response.status);
            }
            return response.json();
        })
        .then(function (result) {
            if (result.success) {
                // Remove the blog card from the page without full reload
                const card = document.getElementById('blog-' + blogId);
                if (card) {
                    // Fade-out animation
                    card.style.transition = 'opacity 0.3s ease';
                    card.style.opacity    = '0';
                    setTimeout(function () {
                        card.remove();
                        // If no blogs left, show empty message
                        if (blogList.querySelectorAll('.blog-card').length === 0) {
                            blogList.innerHTML = '<div class="no-blogs">No blog posts yet. Be the first to share your experience!</div>';
                        }
                    }, 300);
                }
            } else {
                alert(escapeHtml(result.message));
            }
        })
        .catch(function (error) {
            alert('Network error. Could not delete the post.');
            console.error('AJAX Delete Error:', error);
        });
    };


    // ===================================================
    //  RENDER - Blog list from JSON data
    // ===================================================

    function renderBlogList(blogs) {
        if (!blogs || blogs.length === 0) {
            blogList.innerHTML = '<div class="no-blogs">No blog posts yet. Be the first to share your experience!</div>';
            return;
        }

        let html = '';
        for (let i = 0; i < blogs.length; i++) {
            const blog = blogs[i];

            // Format the date
            const dateObj = new Date(blog.created_at);
            const dateStr = dateObj.toLocaleDateString('en-US', {
                year: 'numeric', month: 'short', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            // Escape output to prevent XSS
            const safeTitle   = escapeHtml(blog.title);
            const safeAuthor  = escapeHtml(blog.author_name);
            const safeContent = escapeHtml(blog.content).replace(/\n/g, '<br>');

            html += '<div class="blog-card" id="blog-' + blog.id + '">';
            html += '  <h3>' + safeTitle + '</h3>';
            html += '  <div class="meta">By <strong>' + safeAuthor + '</strong> &nbsp;|&nbsp; ' + dateStr + '</div>';
            html += '  <div class="content">' + safeContent + '</div>';

            // Show delete button if admin or post owner
            if (currentRole === 'admin' || blog.author_id == currentUserId) {
                html += '  <div class="card-footer">';
                html += '    <button class="btn-delete" onclick="deleteBlog(' + blog.id + ')">Delete</button>';
                html += '  </div>';
            }

            html += '</div>';
        }

        blogList.innerHTML = html;
    }


    // ===================================================
    //  UTILITY - Escape HTML to prevent XSS
    // ===================================================

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

});

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// intialize posts file path
$postsFile = __DIR__ . '/../Data/posts.json';
// make sure the posts file exists for security purposes
if (!file_exists($postsFile)) {
    header("Location: blog.php");
    exit;
}

// Load posts data first
$posts = json_decode(file_get_contents($postsFile), true);

// Check if posts is null or not an array
if (!is_array($posts)) {
    $posts = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_SESSION['id'])) {
    $deleteId = intval($_POST['delete_id']);
    $deletedPost = null;
    $imageName = null;
    
    // Find the post to be deleted first (to get image info)
    foreach ($posts as $post) {
        if ($post['id'] == $deleteId) {
            $deletedPost = $post;
            // Get image name if exists
            if (isset($post['image']) && !empty($post['image'])) {
                $imageName = $post['image'];
            }
            break;
        }
    }
    
    // Debug: uncomment these lines to check what's happening
    // echo "Delete ID: " . $deleteId . "<br>";
    // echo "Found post: " . ($deletedPost ? "Yes" : "No") . "<br>";
    // echo "Image name: " . ($imageName ? $imageName : "None") . "<br>";
    // die(); // Remove this after testing
    
    // Filter out the post to be deleted
    $posts = array_filter($posts, function($post) use ($deleteId) {
        return $post['id'] != $deleteId;
    });
    
    // Delete associated image file if it exists
    if ($imageName) {
        $imagePath = __DIR__ . "/../uploads/" . $imageName;
        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                // Image deleted successfully
                error_log("Image deleted successfully: " . $imagePath);
            } else {
                // Failed to delete image
                error_log("Failed to delete image: " . $imagePath);
            }
        } else {
            error_log("Image file not found: " . $imagePath);
        }
    }
    
    // Regenerate the file after deletion (reindex array)
    file_put_contents($postsFile, json_encode(array_values($posts), JSON_PRETTY_PRINT));
}

// Redirect back to the referring page or to blog.php if no referrer
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'blog.php';
header("Location: " . $redirect);
exit;
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$postsFile = __DIR__ . '/../Data/posts.json';

if (!file_exists($postsFile)) {
    header("Location: blog.php");
    exit;
}

$posts = json_decode(file_get_contents($postsFile), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_SESSION['id'])) {
    $deleteId = intval($_POST['delete_id']);

    // filter out the post to be deleted
    $posts = array_filter($posts, function($post) use ($deleteId) {
        return $post['id'] != $deleteId;
    });

    //  regenerate the file after deletion
    file_put_contents($postsFile, json_encode(array_values($posts), JSON_PRETTY_PRINT));
}

// Redirect back to the referring page or to blog.php if no referrer
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'blog.php';
header("Location: " . $redirect);
exit;

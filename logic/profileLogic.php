<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user is not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Get user info from session
$userId   = $_SESSION['id'];
$username = $_SESSION['username'];

// Load users data
$usersFile = __DIR__ . '/../Data/users.json';
$users     = file_exists($usersFile) 
           ? json_decode(file_get_contents($usersFile), true) 
           : [];

// Find current user
$currentUser = null;
foreach ($users as $u) {
    if ($u['id'] == $userId) {
        $currentUser = $u;
        break;
    }
}

// Load posts data
$postsFile = __DIR__ . '/../Data/posts.json';
$posts     = file_exists($postsFile) 
           ? json_decode(file_get_contents($postsFile), true) 
           : [];

// Filter posts belonging to current user
$userPosts = array_filter($posts, function ($post) use ($userId) {
    return $post['user_id'] == $userId;
});

// Sort posts by date (newest first)
usort($userPosts, function ($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
?>

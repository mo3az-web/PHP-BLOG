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
    $profileImage = $currentUser['profile_image'] ?? null;
    $imagePath = ($profileImage && file_exists(__DIR__ . '/../uploads/' . $profileImage)) 
                 ? "../uploads/$profileImage" 
                 : "https://via.placeholder.com/100/cccccc/ffffff?text=👤";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .profile-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #fff;
    }
    .navbar-nav .nav-item + .nav-item {
        margin-left: 10px;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand bg-dark navbar-dark shadow-sm">
  <div class="container">
    <ul class="navbar-nav ms-auto align-items-center">
      <?php if (isset($_SESSION['username'])): ?>
        <li class="nav-item d-flex align-items-center me-3">
          <img src="<?= $imagePath ?>" alt="Profile" class="profile-img me-2">
          <span class="navbar-text text-white-50">
            👋 Welcome, <strong class="text-white"><?= htmlspecialchars($_SESSION['username']) ?></strong>
          </span>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm" href="blog.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm" href="profile.php">Profile</a>
        </li>
        <li class="nav-item ms-2">
          <a class="btn btn-danger btn-sm" href="logout.php">Logout</a>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm" href="index.php">Login</a>
        </li>
        <li class="nav-item ms-2">
          <a class="btn btn-primary btn-sm" href="register.php">Sign Up</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
</body>
</html>

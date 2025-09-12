<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// this is a navbar component i created to make navigation easier

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Blog</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand bg-dark navbar-dark shadow-sm">
  <div class="container">
    

    <ul class="navbar-nav ms-auto align-items-center">
      <?php if (isset($_SESSION['username'])): ?>
        <li class="nav-item me-3">
          <span class="navbar-text text-white-50">
            👋 Welcome, <strong class="text-white"><?= htmlspecialchars($_SESSION['username']) ?></strong>
          </span>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm" href="blog.php">Home</a>
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

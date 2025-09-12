<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../Includes/header.php'; 
$postsFile = __DIR__ . '/../Data/posts.json';

$posts = json_decode(file_get_contents($postsFile), true); 
usort($posts, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

  <h1 class="mb-4">this is  blog feed</h1>
  
        <a  class="btn btn-danger" href="createPost.php">What are you thinking about?</a>
     
  <!-- here posts wil be showing --> 
  <?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
          <h6 class="card-subtitle mb-2 text-muted">
            By <?= htmlspecialchars($post['author']) ?> on <?= $post['date'] ?>
          </h6>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-info">No posts yet. Be the first to post something!</div>
  <?php endif; ?>
</div>
</body>
</html>

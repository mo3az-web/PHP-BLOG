<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include header
include __DIR__ . '/../Includes/header.php';
// Load posts data
$postsFile = __DIR__ . '/../Data/posts.json';
$posts = file_exists($postsFile) ? json_decode(file_get_contents($postsFile), true) : [];

// Sort posts by date (newest first)
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .post-image {
        max-width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
    }
  </style>
</head>
<body class="bg-light">

<div class="container mt-5">
  <h1 class="mb-4">Blog Feed</h1>

  <a href="createPost.php" class="btn btn-danger mb-4">
     What are you thinking about?
  </a>

  <!-- Display posts -->
  <?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
          <h6 class="card-subtitle mb-2 text-muted">
            By <?= htmlspecialchars($post['author'] ?? 'Unknown') ?> on <?= $post['date'] ?>
          </h6>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

          <?php if (!empty($post['image'])): ?>
            <?php $imagePath = __DIR__ . './uploads/' . htmlspecialchars($post['image']); ?>
            <div class="mb-3">
              <img src="<?= "./uploads/" . htmlspecialchars($post['image']) ?>" 
                   class="post-image img-fluid" 
                   alt="Post Image"
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
              <div style="display:none;" class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Image not found: <?= htmlspecialchars($post['image']) ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- Delete button (only for post owner) -->
          <?php if (isset($_SESSION['id']) && $_SESSION['id'] == ($post['user_id'] ?? 0)): ?>
            <form method="post" action="../logic/deletePost.php" class="mt-2">
              <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">
                🗑 Delete
              </button>
            </form>
          <?php endif; ?>
       <?php if (isset($_SESSION['id']) && $_SESSION['id'] == ($post['user_id'] ?? 0)): ?>
    <a href="editPosts.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary mt-2">
        ✏️ Edit
    </a>
<?php endif; ?>


        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-info">No posts yet. Be the first to post something!</div>
  <?php endif; ?>

</div>

</body>
</html>

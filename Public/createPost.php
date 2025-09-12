<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../Includes/header.php'; 
$postsFile = __DIR__ . '/../Data/posts.json';

// if the file doesn't exist, create it
if (!file_exists($postsFile)) {
    file_put_contents($postsFile, json_encode([]));
}
$posts = json_decode(file_get_contents($postsFile), true);

/*  this part is moved to deletePost.php i used to use it to delete posts but better to separate  it from this file becuse its used in onther files



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $posts = array_filter($posts, function($post) use ($deleteId) {
        return $post['id'] != $deleteId;
    });
    file_put_contents($postsFile, json_encode(array_values($posts), JSON_PRETTY_PRINT));
    header("Location: blog.php");
    exit;
}*/

// adding new post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_SESSION['id'])) {
    $newPost = [
        "id" => count($posts) ? end($posts)['id'] + 1 : 1,
        "title" => $_POST['title'],
        "content" => $_POST['content'],
        "author" => $_SESSION['username'],
        "user_id" => $_SESSION['id'],
        "date" => date("Y-m-d H:i:s")
    ];

    $posts[] = $newPost;
    file_put_contents($postsFile, json_encode($posts, JSON_PRETTY_PRINT));

    header("Location: blog.php");
    exit;
}
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

  <!-- add posts but only if user is logged in  -->
  <?php if (isset($_SESSION['username'])): ?>
    <div class="card mb-5 shadow-sm">
      <div class="card-body">
        <a class="btn btn-danger btn-sm" href="blog.php">X</a>
        <h5 class="card-title">What are you thinking about?</h5>
        <form method="post">
          <div class="mb-3">
            <input type="text" name="title" class="form-control" placeholder="Title" required>
          </div>
          <div class="mb-3">
            <textarea name="content" class="form-control" rows="5" placeholder="Write your post..." required></textarea>
          </div>
          <button type="submit" class="btn btn-danger">Post</button>
        </form>
      </div>
    </div>
  <?php endif; ?>

   <!-- all data will be mapped and added here -->
  <?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
          <h6 class="card-subtitle mb-2 text-muted">
            By <?= htmlspecialchars($post['author']) ?> on <?= $post['date'] ?>
          </h6>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

         <!-- زر الحذف -->
<?php if (isset($_SESSION['id']) && $_SESSION['id'] == $post['user_id']): ?>
  <form method="post" action="deletePost.php" class="mt-2">
    <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Delete</button>
  </form>
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

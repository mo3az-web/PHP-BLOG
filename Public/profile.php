<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];
$username = $_SESSION['username'];

// here is our little dataBase
$usersFile = __DIR__ . '/../Data/users.json';
$users = json_decode(file_get_contents($usersFile), true);

// fetch current user data
$currentUser = null;
foreach ($users as $u) {
    if ($u['id'] == $userId) {
        $currentUser = $u;
        break;
    }
}
//  fetch posts data
$postsFile = __DIR__ . '/../Data/posts.json';
$posts = file_exists($postsFile) ? json_decode(file_get_contents($postsFile), true) : [];

//  filter posts by current user
$userPosts = array_filter($posts, function($post) use ($userId) {
    return $post['user_id'] == $userId;
});


// listing posts by date (newest first)
usort($userPosts, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($username) ?>'s Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../Includes/header.php'; ?>

<div class="container mt-5">

  <!-- user data will be showen here -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h3 class="card-title">👤 Profile</h3>
      <form method="post" action="deleteUser.php" class="d-inline">
    <input type="hidden" name="delete_id" value="<?= $user['id']; ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Delete my account</button>
</form>

      <p><strong>Username:</strong> <?= htmlspecialchars($currentUser['username']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($currentUser['email']) ?></p>
    </div>
  </div>

  <!-- show posts of the current user -->
  <h4 class="mb-3">📝 Your Posts</h4>
  
  <?php if (count($userPosts) > 0): ?>
    <?php foreach ($userPosts as $post): ?>
      <!-- dellete button -->
<?php if (isset($_SESSION['id']) && $_SESSION['id'] == $post['user_id']): ?>
  <form method="post" action="deletePost.php" class="mt-2">
    <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Delete</button>
  </form>
<?php endif; ?>

      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
          <h6 class="card-subtitle mb-2 text-muted">
            Posted on <?= $post['date'] ?>
          </h6>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-info">You haven't posted anything yet.</div>
  <?php endif; ?>

</div>


</body>
</html>

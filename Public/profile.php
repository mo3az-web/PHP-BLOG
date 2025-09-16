<?php
include __DIR__ . '/../logic/profileLogic.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($username) ?>'s Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .profile-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ccc;
    }
    .post-image {
        max-height: 400px;
        object-fit: cover;
    }
  </style>
</head>
<body class="bg-light">

<?php include __DIR__ . '/../Includes/header.php'; ?>

<div class="container mt-5">

  <!-- user profile -->
  <?php
    $profileImage = $currentUser['profile_image'] ?? null;
    $imagePath = ($profileImage && file_exists(__DIR__ . '/../uploads/' . $profileImage)) 
                 ? "../uploads/$profileImage" 
                 : "https://via.placeholder.com/100/cccccc/ffffff?text=👤";
  ?>
  <div class="card mb-4 shadow-sm">
    <div class="card-body d-flex align-items-center">
      <img src="<?= $imagePath ?>" alt="Profile" class="profile-img me-3">
      <div>
        <h3 class="card-title"> Profile</h3>
        <p><strong>Username:</strong> <?= htmlspecialchars($currentUser['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($currentUser['email']) ?></p>
        <form method="post" action="deleteUser.php" class="d-inline">
          <input type="hidden" name="delete_id" value="<?= $currentUser['id']; ?>">
          <a href="editProfile.php" class="btn btn-sm btn-outline-primary">✏️ Edit Profile</a>
          <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Delete my account</button>
        </form>
      </div>
    </div>
  </div>

  <!-- user's posts -->
  <h4 class="mb-3">📝 Your Posts</h4>
  
  <?php if (count($userPosts) > 0): ?>
    <?php foreach ($userPosts as $post): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
          <h6 class="card-subtitle mb-2 text-muted">
            Posted on <?= $post['date'] ?>
          </h6>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

          <?php if (!empty($post['image'])): ?>
            <?php $imagePath = "../uploads/" . htmlspecialchars($post['image']); ?>
            <div class="mb-3">
              <img src="<?= $imagePath ?>" class="post-image img-fluid" alt="Post Image" 
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
              <div style="display:none;" class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> الصورة غير موجودة: <?= htmlspecialchars($post['image']) ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- delete button -->
        <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $post['user_id']): ?>
            <form method="post" action="../logic/deletePost.php" class="mt-2">
              <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger" 
                      onclick="return confirm('هل تريد حذف هذا البوست؟')">🗑 Delete</button>
            </form>
          <?php endif; ?>

        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-info">You haven't posted anything yet.</div>
  <?php endif; ?>

</div>
</body>
</html>

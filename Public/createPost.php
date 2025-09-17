<?php

include __DIR__ . '/../logic/PostingLogic.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

  <!-- إضافة بوست -->
  <?php if (isset($_SESSION['username'])): ?>

    <div class="card mb-5 shadow-sm">
      <div class="card-body">
            <a href="blog.php" class="btn btn-danger">
   X
</a>

        <h5 class="card-title">What are you thinking about?</h5>
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <input type="text" name="title" class="form-control" placeholder="Title" required>
          </div>
          <div class="mb-3">
            <textarea name="content" class="form-control" rows="5" placeholder="Write your post..." required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Choose image:</label>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
            <div class="form-text">Supported formats: JPG, PNG, GIF, WebP (Max size: 5MB)</div>
          </div>
          <button type="submit" class="btn btn-danger">Post</button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <!-- عرض البوستات -->
  <?php if (count($posts) > 0): ?>
    <?php foreach (array_reverse($posts) as $post): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
          <h6 class="card-subtitle mb-2 text-muted">
            By <?= htmlspecialchars($post['author']) ?> on <?= $post['date'] ?>
          </h6>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

          <?php if (!empty($post['image'])): ?>
            <?php 
            // تحديد المسار الصحيح للصورة
            $imagePath = "uploads/" . htmlspecialchars($post['image']);
            ?>
            <div class="mb-3">
              <img src="<?= $imagePath ?>" class="post-image img-fluid" alt="Post Image" 
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
              <div style="display:none;" class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> الصورة غير موجودة: <?= htmlspecialchars($post['image']) ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- زر الحذف -->
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
    <div class="alert alert-info">No posts yet. Be the first to post something!</div>
  <?php endif; ?>

</div>

<script>
// لتجربة الصور قبل النشر
document.querySelector('input[type="file"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // التحقق من حجم الملف (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('حجم الملف كبير جداً. الحد الأقصى 5MB');
            this.value = '';
            return;
        }
        
        // عرض معاينة للصورة
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById('image-preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'image-preview';
                preview.className = 'img-thumbnail mt-2';
                preview.style.maxHeight = '200px';
                document.querySelector('input[type="file"]').parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>
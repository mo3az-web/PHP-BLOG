<?php
  include __DIR__ . '/../logic/editPost.php';
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Your Post</h2>
    <div class="card shadow-sm p-4">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Post Title</label>
                <input type="text" class="form-control" id="title" name="title" required
                       value="<?= htmlspecialchars($currentPost['title']) ?>">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="6" required><?= htmlspecialchars($currentPost['content']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Post Image (optional)</label>
                <?php if (!empty($currentPost['image']) && file_exists(__DIR__ . '/../uploads/' . $currentPost['image'])): ?>
                    <div class="mb-2">
                        <img src="../uploads/<?= htmlspecialchars($currentPost['image']) ?>" alt="Post Image"
                             style="max-width:200px; border-radius:8px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="blog.php" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>

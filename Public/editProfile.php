<?php
  include __DIR__ . '/../logic/editProfileLogic.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ccc;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm p-4 text-center">
        <h3 class="card-title mb-4">✏️ Edit Profile</h3>
        
        <!-- Profile image -->
        <img src="<?= $imagePath ?>" alt="Profile" class="profile-img">

        <form method="post" enctype="multipart/form-data">
            <div class="mb-3 text-start">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($currentUser['username']) ?>" required>
            </div>
            <div class="mb-3 text-start">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($currentUser['email']) ?>" required>
            </div>
            <div class="mb-3 text-start">
                <label>Profile Image</label>
                <input type="file" name="profile_image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>

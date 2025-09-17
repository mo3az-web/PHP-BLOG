<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../Includes/header.php'; 

$postsFile = __DIR__ . '/../Data/posts.json';

// Real folder path on the server (for storing images)
$uploadDir = __DIR__ . '/../public/uploads/';
// Browser-accessible path (for displaying images)
$uploadUrl = 'uploads/';

// Make sure posts file exists
if (!file_exists($postsFile)) {
    file_put_contents($postsFile, json_encode([]));
}

// Read existing posts
$posts = json_decode(file_get_contents($postsFile), true);

// Handle image upload
$image = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $_FILES['image']['type'];

    if (!in_array($fileType, $allowedTypes)) {
        echo "<div class='alert alert-danger'>Unsupported file type. Please upload JPG, PNG, GIF, or WebP.</div>";
    } else {
        // Create upload folder if it does not exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique file name
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $imageName;

        // Move uploaded file to uploads directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $imageName; // Store only the file name in JSON
        } else {
            echo "<div class='alert alert-danger'>Failed to upload the image.</div>";
        }
    }
}

// Add new post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_SESSION['id'])) {
    $newPost = [
        "id" => count($posts) ? end($posts)['id'] + 1 : 1,
        "title" => $_POST['title'],
        "content" => $_POST['content'],
        "author" => $_SESSION['username'],
        "user_id" => $_SESSION['id'],
        "image" => $image ?? null,
        "date" => date("Y-m-d H:i:s")
    ];

    $posts[] = $newPost;
    file_put_contents($postsFile, json_encode($posts, JSON_PRETTY_PRINT));

    header("Location: blog.php");
    exit;
}
?>

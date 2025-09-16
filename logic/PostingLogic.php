<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include __DIR__ . '/../Includes/header.php'; 
$postsFile = __DIR__ . '/../Data/posts.json';
$uploadDir = __DIR__ . '/../uploads/';

// make sure posts file exists for security purposes
if (!file_exists($postsFile)) {
    file_put_contents($postsFile, json_encode([]));
}

// read existing posts
$posts = json_decode(file_get_contents($postsFile), true);

// upload image if exists
$image = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    // validate image type and size 
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $_FILES['image']['type'];
     // check if the uploaded file type is allowed
    if (!in_array($fileType, $allowedTypes)) {
        echo "<div class='alert alert-danger'> this file is not supported please upload it in JPG, PNG, GIF or WebP formats </div>";
    } else {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
   
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $imageName;
        } else {
            echo "<div class='alert alert-danger'>failed to upload the picture check  </div>";
        }
    }
}

// add new post if form submitted to the json files 
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
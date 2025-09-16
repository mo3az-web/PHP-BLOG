<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// make sure user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// get posts data
$postsFile = __DIR__ . '/../Data/posts.json';
$posts = file_exists($postsFile) ? json_decode(file_get_contents($postsFile), true) : [];

// get posts id from query string
if (!isset($_GET['id'])) {
    header("Location: blog.php");
    exit;
}

$editId = intval($_GET['id']);
$currentPost = null;

foreach ($posts as $post) {
    if ($post['id'] == $editId && $post['user_id'] == $_SESSION['id']) {
        $currentPost = $post;
        break;
    }
}

if (!$currentPost) {
    header("Location: blog.php");
    exit;
}

// form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $imageName = $currentPost['image'] ?? null;

    // handle image upload
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/';
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($imageTmp, $uploadDir . $imageName);
        // delete old image if exists from server (uploads folder)
        if (!empty($currentPost['image']) && file_exists($uploadDir . $currentPost['image'])) {
            unlink($uploadDir . $currentPost['image']);
        }
    }

    // update post in array
    foreach ($posts as &$post) {
        if ($post['id'] == $editId) {
            $post['title'] = $title;
            $post['content'] = $content;
            $post['image'] = $imageName;
            //delete this feature beacuse its not required
           // $post['date'] = date('Y-m-d H:i:s'); // update date to current
            break;
        }
    }
// save updated posts json back to file
    file_put_contents($postsFile, json_encode(array_values($posts), JSON_PRETTY_PRINT));

    header("Location:/blogproject/Public/blog.php");
    exit;
}
?>
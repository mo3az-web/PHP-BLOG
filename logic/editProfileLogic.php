<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];

// include utility functions for reading/writing JSON
include __DIR__ . '/../Includes/jsonUtils.php';  

// load users
$usersFile = __DIR__ . '/../Data/users.json';
$users = readJson($usersFile);

// find current user
$currentUser = null;
foreach ($users as $index => $u) {
    if ($u['id'] == $userId) {
        $currentUser = $u;
        $currentIndex = $index;
        break;
    }
}

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = trim($_POST['username']);
    $newEmail = trim($_POST['email']);

    // update user data in array
    $users[$currentIndex]['username'] = $newUsername;
    $users[$currentIndex]['email'] = $newEmail;

    // handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $targetDir = __DIR__ . '/../uploads/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageName = time() . "_" . basename($_FILES['profile_image']['name']);
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            $users[$currentIndex]['profile_image'] = $imageName;
        }
    }
if (!empty($currentUser ['profile_image'])) {
        // delete old image file if exists
        $oldImage = __DIR__ . '/../uploads/' . $currentUser['profile_image'];
        if (file_exists($oldImage)) {
            unlink($oldImage);
        }
    }
    // save changes to JSON file
    writeJson($usersFile, $users);

    // update username and profile_image in session
    $_SESSION['username'] = $newUsername;
    $_SESSION['profile_image'] = $users[$currentIndex]['profile_image'] ?? null;

    // redirect back to profile page
    header("Location: profile.php");
    exit;
}

// determine profile image
$profileImage = $currentUser['profile_image'] ?? null;
$imagePath = ($profileImage && file_exists(__DIR__ . '/../uploads/' . $profileImage)) 
             ? "../uploads/$profileImage" 
             : "https://via.placeholder.com/100/cccccc/ffffff?text=👤";
?>

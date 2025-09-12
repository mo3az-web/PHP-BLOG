<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usersFile = __DIR__ . '/../Data/users.json';

if (!file_exists($usersFile)) {
    header("Location: index.php"); // if the file doesn't exist, redirect to home
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id'])) {
    $currentUserId = $_SESSION['id']; // get current user id from session

    // filter out the user to be deleted
    $filteredUsers = [];
    foreach ($users as $user) {
        if ($user['id'] != $currentUserId) {
            $filteredUsers[] = $user;
        } else {
            $success = true; // delete successful
        }
    }

    //  regenerate the file after deletion
    if ($success) {
        if(file_put_contents($usersFile, json_encode($filteredUsers, JSON_PRETTY_PRINT)) === false){
            die("❌ Error writing users file!");
        }

        // clear session and cookies after user deletion
        session_unset();
        session_destroy();
    }
}

// Redirect to home page
$redirect = 'index.php';
header("Location: " . $redirect);
exit;

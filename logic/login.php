<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = json_decode(file_get_contents(__DIR__ . '/../Data/users.json'), true);
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    $validUser = null;

    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            $validUser = $user;
            break;
        }
    }

    if ($validUser) {
        // open a new session and store user data in it 
        $_SESSION['id'] = $validUser['id'];         // user id
        $_SESSION['username'] = $validUser['username']; // user name

        // set a cookie for the user 
        setcookie("newUserCookie", $validUser['username'], time() + 3600, "/");
       
        header("Location: blog.php");
        exit;
    } else {
        $error = "❌ Wrong email or password";
    }
}
?>

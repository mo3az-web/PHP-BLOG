<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // start the session if not already started
  // include utility functions for JSON handling
    require_once '../Includes/jsonUtils.php';
    // get and sanitize form inputs
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    // collect validation errors
    $errors = [];
    // validate inputs
    if (empty($username)) {
        $errors[] = "❌ Name is required";
    }

    if (empty($email) ) {
        $errors[] = "❌ Valid email is required";
    }elseif((!filter_var($email, FILTER_VALIDATE_EMAIL))){
        $errors[] = "❌ Invalid email format";
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = "❌ Password must be at least 6 characters";
    }
    if (empty($id) || strlen($id) < 3) {
        $errors[] = "❌ user id must be at least 3 characters";
    }

    if (empty($errors)) {
        $users = readJson(__DIR__ . '/../Data/users.json');

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $errors[] = "❌ Email is already registered";
                break;
            }
        }
        foreach ($users as $user) {
            if ($user['id'] === $id) {
                $errors[] = "❌id is already registered";
                break;
            }
        }
  //create new user if no errors
        if (empty($errors)) {
            $users[] = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'id' => $id
            ];

            writeJson(__DIR__ . '/../Data/users.json', $users);

            header("Location: index.php"); 
            exit;
        }
    }
}
?>
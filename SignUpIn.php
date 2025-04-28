<?php
session_start();

$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $action = $_POST['action'];

    if (empty($email) || empty($password)) {
        header("Location: signinorupfirst.html?error=emptyfields");
        exit();
    }

    if ($action == 'signin') {
        if (isset($users[$email]) && password_verify($password, $users[$email])) {
            $_SESSION['user_email'] = $email;
            header("Location: create.html"); // Redirect to create.html after successful sign in
            exit();
        } else {
            header("Location: signinorupfirst.html?error=invalidlogin");
            exit();
        }
    } elseif ($action == 'signup') {
        if (isset($users[$email])) {
            header("Location: signinorupfirst.html?error=userexists");
            exit();
        } else {
            $users[$email] = password_hash($password, PASSWORD_DEFAULT);
            file_put_contents($usersFile, json_encode($users));
            $_SESSION['user_email'] = $email;
            header("Location: create.html"); // Redirect to create.html after successful sign up
            exit();
        }
    } else {
        header("Location: signinorupfirst.html?error=unknownaction");
        exit();
    }
} else {
    header("Location: signinorupfirst.html?error=invalidrequest");
    exit();
}
?>
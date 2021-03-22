<?php
//register new user

//OBS! fixa så det blir rätt sökväg för filerna
include("C:/xampp/htdocs/tea_project/config/database_handler.php");
include("C:/xampp/htdocs/tea_project/objects/Users.php");

$username = "";
$user_password = "";
$user_email = "";
$user_role = "";

if(!empty($_GET['username'])) {
    $username = $_GET['username'];
}

if(!empty($_GET['password'])) {
    $user_password = $_GET['password'];
}

if(!empty($_GET['email'])) {
    $user_email = $_GET['email'];
}

if(!empty($_GET['role'])) {
    $user_role = $_GET['role'];
}

$user = new Users($pdo);
$user->createUser($username, $user_password, $user_email, $user_role);

?>
<?php
//login user

//OBS! fixa så det blir rätt sökväg för filerna
include("C:/xampp/htdocs/tea_project/config/database_handler.php");
include("C:/xampp/htdocs/tea_project/objects/Users.php");

$username = "";
$user_password = "";

if(!empty($_GET['username'])) {
    $username = $_GET['username'];
}

if(!empty($_GET['password'])) {
    $user_password = $_GET['password'];
}

$user = new Users($pdo);
$user->loginUser($username, $user_password);

?>  
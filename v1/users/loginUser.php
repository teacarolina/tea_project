<?php
//login user
include("../../config/database_handler.php");
include("../../objects/Users.php");

if(!empty($_POST['username'])) {
    $username = $_POST['username'];
}

if(!empty($_POST['password'])) {
    $user_password = $_POST['password'];
    $salt = "jfjeale8?=)/ghhda54#";
    $user_password = md5($user_password.$salt);
}

$user = new Users($pdo);
$user->loginUser($username, $user_password);

?>  
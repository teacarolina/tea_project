<?php
//register new user
include("../../config/database_handler.php");
include("../../objects/Users.php");

if(!empty($_POST['username'])) {
    $username = $_POST['username'];
}

//salt and md5 crypt is added to the password to secure it
if(!empty($_POST['password'])) {
    $user_password = $_POST['password'];
    $salt = "jfjeale8?=)/ghhda54#";
    $user_password = md5($user_password.$salt);
}

if(!empty($_POST['email'])) {
    $user_email = $_POST['email'];
}

$user = new Users($pdo);           
$user->createUser($username, $user_password, $user_email);

?>
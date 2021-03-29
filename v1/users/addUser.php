<?php
//register new user
include("../../config/database_handler.php");
include("../../objects/Users.php");

$username = "";
$user_password = "";
$user_email = "";

if(!empty($_GET['username'])) {
    $username = $_GET['username'];
}

//salt and md5 crypt is added to the password to secure it
if(!empty($_GET['password'])) {
    $user_password = $_GET['password'];
    $salt = "jfjeale8?=)/ghhda54#";
    $user_password = md5($user_password.$salt);
}

if(!empty($_GET['email'])) {
    $user_email = $_GET['email'];
}

$user = new Users($pdo);           
$user->createUser($username, $user_password, $user_email);

?>
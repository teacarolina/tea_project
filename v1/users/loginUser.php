<?php
//login user
include("C:/xampp/htdocs/tea_project/config/database_handler.php");
include("C:/xampp/htdocs/tea_project/objects/Users.php");

$user = new Users($pdo);
$user->loginUser("username", "password");

?>
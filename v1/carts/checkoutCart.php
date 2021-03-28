<?php
//cart checkout

//OBS! fixa så det blir rätt sökväg för filerna
include("C:/xampp/htdocs/tea_project/config/database_handler.php");
include("C:/xampp/htdocs/tea_project/objects/Carts.php");

$username = "";

if(!empty($_GET['username'])) {
    $username = $_GET['username'];
}

$carts = new Carts($pdo);
$carts->checkoutCart($username);

?>
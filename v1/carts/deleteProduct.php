<?php
//delete product from cart

//OBS! fixa så det blir rätt sökväg för filerna
include("C:/xampp/htdocs/tea_project/config/database_handler.php");
include("C:/xampp/htdocs/tea_project/objects/Carts.php");

$username = "";
$product_id = "";

if(!empty($_GET['username'])) {
    $username = $_GET['username'];
}

if(!empty($_GET['productid'])) {
    $product_id = $_GET['productid'];
}

$carts = new Carts($pdo);
$carts->deleteFromCart($username, $product_id);

?>
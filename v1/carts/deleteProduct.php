<?php
//delete product from cart
include("../../config/database_handler.php");
include("../../objects/Carts.php");

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
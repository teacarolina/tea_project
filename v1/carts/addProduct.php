<?php
//add product to cart
include("../../config/database_handler.php");
include("../../objects/Carts.php");

$product_id = "";
$product_quantity = "";
$username = "";
// $cart_create_date = "";

if(!empty($_GET['productid'])) {
     $product_id = $_GET['productid'];
}

if(!empty($_GET['quantity'])) {
    $product_quantity = $_GET['quantity'];
}

if(!empty($_GET['username'])) {
    $username = $_GET['username'];
}

$cart = new Carts($pdo);
$cart->addToCart($username, $product_id, $product_quantity);

?>
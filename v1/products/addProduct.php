<?php
//add new product

//OBS! fixa så det blir rätt sökväg för filerna
include("C:/xampp/htdocs/tea_project/config/database_handler.php");
include("C:/xampp/htdocs/tea_project/objects/Products.php");

$product_name = "";
$product_description = "";
$product_price = "";

if(!empty($_GET['productname'])) {
    $product_name = $_GET['productname'];
}

if(!empty($_GET['description'])) {
    $product_description = $_GET['description'];
}

if(!empty($_GET['price'])) {
    $product_price = $_GET['price'];
}

$product = new Products($pdo);
$product->createProduct($product_name, $product_description, $product_price);

?>
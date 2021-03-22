<?php
//delete product

//OBS! fixa så det blir rätt sökväg för filerna
include("C:/xampp/htdocs/tea_project/config/database_handler.php");
include("C:/xampp/htdocs/tea_project/objects/Products.php");

$product_id = "";

if(!empty($_GET['id'])) {
    $product_id = $_GET['id'];
}

$product = new Products($pdo);
$product->deleteProduct($product_id);

?>
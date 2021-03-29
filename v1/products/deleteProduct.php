<?php
//delete product
include("../../config/database_handler.php");
include("../../objects/Products.php");

$product_id = "";

if(!empty($_GET['id'])) {
    $product_id = $_GET['id'];
}

$product = new Products($pdo);
$product->deleteProduct($product_id);

?>
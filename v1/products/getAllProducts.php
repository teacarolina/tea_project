<?php
//list all products
include("../../config/database_handler.php");
include("../../objects/Products.php");

$product = new Products($pdo);
$product->getAllProducts();

?>
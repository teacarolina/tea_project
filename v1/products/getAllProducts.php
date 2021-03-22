<?php
//list all products

//OBS! fixa så det blir rätt sökväg för filerna
include("C:/xampp/htdocs/tea_project/config/database_handler.php");
include("C:/xampp/htdocs/tea_project/objects/Products.php");

$product = new Products($pdo);
$product->getAllProducts();

?>
<?php
//search products by product name 
include("../../config/database_handler.php");
include("../../objects/Products.php");

$search_word = "";

if(!empty($_GET['word'])) {
    $search_word = $_GET['word'];
}

$product = new Products($pdo);
$product->searchProductName($search_word);

?>
<?php
//cart checkout
include("../../config/database_handler.php");
include("../../objects/Carts.php");

$username = "";

if(!empty($_GET['username'])) {
    $username = $_GET['username'];
}

$carts = new Carts($pdo);
$carts->checkoutCart($username);

?>
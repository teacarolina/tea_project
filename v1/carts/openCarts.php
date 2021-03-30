<?php
//sum of open carts
include("../../config/database_handler.php");
include("../../objects/Carts.php");

$carts = new Carts($pdo);
$carts->sumOpenCarts();

?>
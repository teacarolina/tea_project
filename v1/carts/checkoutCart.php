<?php
//cart checkout
// echo "<table>" . "<tr>" . "<th>Product name</th>" . "<th>Price</th>" . "<th>Quantity</th>" . "</tr>" . 
// "<tr>" . "<td>Product name</td>" . "<td>100</td>" . "<td>1</td>" . "</tr>" . 
// "<tr>" . "<th>Total amount:</th>" .  "<td>100</td>" . "</tr>" .
// "</table>";

//SELECT SUM(products.Price*carts.Quantity) FROM carts 
//JOIN products ON products.Id = carts.ProductId
//WHERE carts.Id = 15;

//OBS! fixa så det blir rätt sökväg för filerna
include("C:/xampp/htdocs/tea_project/config/database_handler.php");
include("C:/xampp/htdocs/tea_project/objects/Carts.php");

$username = "";

if(!empty($_GET['username'])) {
    $username = $_GET['username'];
}

$carts = new Carts($pdo);
$carts->checkoutCart($username);

?>
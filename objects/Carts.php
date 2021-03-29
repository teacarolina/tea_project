<?php 

class Carts {

    private $database_connection;
    private $id;
    private $product_name; 
    private $price;
    private $quantity; 

    function __construct($db) {

        $this->database_connection = $db;
    }
  
    function addToCart($username_IN, $product_id_IN, $product_quantity_IN) {
        
        $error = new stdClass();
        if(!empty($product_id_IN) && !empty($product_quantity_IN)) {

            $sql = "SELECT ProductName FROM products WHERE id = :product_id_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":product_id_IN", $product_id_IN);
            
            if(!$statement->execute()) {
                $error->message = "Could not execute query";
                $error->code = "0001";
                print_r(json_encode($error));
                die();   
            }
            
            $number_of_rows = $statement->rowCount();
            if($number_of_rows < 1) {
                $error->message = "Product doesn't exist";
                $error->code = "0008";
                print_r(json_encode($error));
                die();
            }

            $validate_token = $this->validateToken($username_IN);

            //kan jag här skriva om koden så den går efter token?? select cart where token is token?
            //då kanske jag inte behöver skriva username i functionen när jag kallar på den? 

            $sql = "SELECT carts.Id FROM carts JOIN users ON carts.UserId = users.Id WHERE users.username = :username_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->execute();

            $row = $statement->fetch();
            $cart_id_IN = $row['Id'];

            $sql = "INSERT INTO cartitems (CartId, ProductId, quantity) VALUES (:cart_id_IN, :product_id_IN, :product_quantity_IN)";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":cart_id_IN", $cart_id_IN);
            $statement->bindParam(":product_id_IN", $product_id_IN);
            $statement->bindParam(":product_quantity_IN", $product_quantity_IN);

            if(!$statement->execute()) {
                $error->message = "Could not execute query";
                $error->code = "0001";
                print_r(json_encode($error));
                die();   
            }

            $update_token = $this->updateToken($username_IN);

            echo "Product added to cart.";
            die();

        } else {
            $error->message = "All arguments needs a value";
            $error->code = "0004";
            print_r(json_encode($error));
            die();
        }
    }

    function validateToken($username_IN) {

        $sql = "SELECT token, TimeCreated FROM carts JOIN users ON carts.UserId = users.Id WHERE users.username = :username_IN AND TimeCreated > :active_time_IN";        
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":username_IN", $username_IN);
       
        $active_time = (new DateTime())->modify('-1 hours')->format('Y-m-d H:i:s');

        $statement->bindParam(":active_time_IN", $active_time);
        $statement->execute();

        if($row = $statement->fetch()) {
             //kan användas i andra funktionen att referera till? 
            return $row['token'];

        } else {
            $sql = "SELECT token, TimeCreated FROM carts JOIN users ON carts.UserId = users.Id WHERE users.username = :username_IN AND TimeCreated < :active_time_IN";        
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
       
            $active_time = (new DateTime())->modify('-1 hours')->format('Y-m-d H:i:s');
     
            $statement->bindParam(":active_time_IN", $active_time);
            $statement->execute();

            if($row = $statement->fetch()) {
            $token = $row['token'];

            $sql = "DELETE FROM carts WHERE token = :token_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":token_IN", $token);
            $statement->execute();
            
            //lägg error message??
            //ska det vara två else?
            echo "Old session have ended and the cart have been emptied. Log in to start session";
            die();
            } else {
                echo "Old session ended. Log in to start session";
                die();
            }
        } 
    }

    function deleteFromCart($username_IN, $product_id_IN) {

        //kan man göra samma här med return token????
        $error = new stdClass();
        if(!empty($username_IN) && !empty($product_id_IN)) {
        $sql = "SELECT cartitems.Id FROM cartitems JOIN carts ON cartitems.CartId = carts.Id JOIN users ON carts.UserId = users.Id WHERE username = :username_IN AND cartitems.ProductId = :product_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":username_IN", $username_IN);
        $statement->bindParam(":product_id_IN", $product_id_IN);
        $statement->execute();

        $number_of_rows = $statement->rowCount();
          
        if($number_of_rows < 1) {
            $error->message = "Product doesn't exist";
            $error->code = "0008";
            print_r(json_encode($error));
            die();   
        }
        
        $row = $statement->fetch();
        $cart_item_id = $row['Id'];
        
        $sql = "DELETE FROM cartitems WHERE id = :cart_item_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":cart_item_id_IN", $cart_item_id);
        $statement->execute();

        $update_token = $this->updateToken($username_IN);

        $this->id = $product_id_IN;

        echo "Product with id $this->id deleted.";
        die();

        } else {
            $error->message = "All arguments needs a value";
            $error->code = "0004";
            print_r(json_encode($error));
            die();
        }
    }

    function checkoutCart($username_IN) {   

        $sql = "SELECT * FROM carts JOIN cartitems ON carts.Id = cartitems.CartId JOIN users ON carts.UserId = users.Id JOIN products ON cartitems.ProductId = products.Id WHERE users.Username = :username_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":username_IN", $username_IN);
        $statement->execute();

        $validate_token = $this->validateToken($username_IN);

        echo "<table>" . "<tr>" . "<th>Product name</th>" . "<th>Price</th>" . "<th>Quantity</th>" . "</tr>";
        $total = 0; 
        while($row = $statement->fetch()) {
            $this->product_name = $row['ProductName'];
            $this->price = $row['Price'];
            $this->quantity = $row['Quantity'];
            $total = $total + ($row['Price'] * $row['Quantity']);
        
            echo "<tr>" . "<td>$this->product_name</td>" . "<td>$this->price</td>" . "<td>$this->quantity</td>" . "</tr>";
        }
        echo "<tr>" . "<th>Total amount:</th>" .  "<td>$total</td>" . "</tr>" . "</table>";
    }

    function updateToken($username_IN) {
        
        $sql = "SELECT Id FROM users WHERE username = :username_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":username_IN", $username_IN);
        $statement->execute();

        $row = $statement->fetch();
        $user_id = $row['Id'];

        $sql = "UPDATE carts SET TimeCreated = :update_time_created WHERE UserId = :user_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $date = (new DateTime())->format('Y-m-d H:i:s');
        $statement->bindParam(":update_time_created", $date);
        $statement->bindParam(":user_id_IN", $user_id);
        $statement->execute();
    }
}

?>
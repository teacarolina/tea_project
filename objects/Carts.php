<?php 

//vilka private $ ska vara med? 
class Carts {
    private $database_connection;
    private $cart_id;
    private $product_id; 
    private $product_quantity; 
    private $cart_time_created;
    private $cart_time_updated;
    private $token; 
    private $user_id; 
    private $product_name; 
    private $product_price; 
    private $username; 
    private $user_password;
    private $user_email; 

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
            //tänk om här kommer inte gå om det inte finns någon session för den användaren? 
            $sql = "SELECT token, TimeCreated FROM carts JOIN users ON carts.UserId = users.Id WHERE users.username = :username_IN AND TimeCreated < :active_time_IN";        
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
       
            $active_time = (new DateTime())->modify('-1 hours')->format('Y-m-d H:i:s');
     
            $statement->bindParam(":active_time_IN", $active_time);
            $statement->execute();

            $row = $statement->fetch();
            $token = $row['token'];

            $sql = "DELETE FROM carts WHERE token = :token_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":token_IN", $token);
            $statement->execute();

            echo "Old session ended. Log in to start session";
            die();
        }
    }
}
?>
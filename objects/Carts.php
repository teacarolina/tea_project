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
  
    //OBS! hur lägger jag till flera produkter i samma cart?
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
            echo "PRODUKTEN FINNS INTE-meddelande";
            //$error->message = "The product is already registered"
            //$error->code = "0006";
            //print_r(json_encode($error));
            die();
            }

            //hur får jag med token så att när man har loggat in så har man samma kundvagn i en timme?
            //ska vagnen skapas i samband med första produkten istället? 

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
                echo "KUNDE INTE LÄGGA TILL I VARUKORG-meddelande";
                
//                 //$error->message = "Could not create product";
//                 //$error->code = "0007";
//                 //print_r(json_encode($error));
                die();
            }

                //$this->productname = $product_name_IN;
             //$this->description = $product_description_IN;
             //$this->price = $product_price_IN;
             //echo "Product created. Product name: $this->productname, Description: $this->description, Price: $this->price";
             //die();
            
                echo "Product added";
                die(); 

         } else {
             $error->message = "All arguments needs a value";
             $error->code = "0004";
             print_r(json_encode($error));
             die();
            }
    }
 }
?>
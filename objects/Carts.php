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
//     function addToCart($product_id_IN, $product_quantity_IN, $cart_create_date_IN, $user_id_IN) {

//         //OBS! kan man lägga den här utanför så alla når samma?
//         $error = new stdClass();
//         if(!empty($product_id_IN) && !empty($product_quantity_IN) && !empty($user_id_IN)) {

//             $sql = "SELECT ProductName FROM products WHERE id = :product_id_IN";
//             $statement = $this->database_connection->prepare($sql);
//             $statement->bindParam(":product_id_IN", $product_id_IN);

//             if(!$statement->execute()) {
//                 $error->message = "Could not execute query";
//                 $error->code = "0001";
//                 print_r(json_encode($error));
//                 die();   
//             }

//             $number_of_rows = $statement->rowCount();
//             if($number_of_rows < 1) {
//                 //PRODUKTEN FINNS INTE-meddelande

//                 //$error->message = "The product is already registered";
//                 //$error->code = "0006";
//                 //print_r(json_encode($error));
//                 //die();
//             }

//             $sql = "INSERT INTO carts (ProductId, quantity, CreateDate, UserId) VALUES (:product_id_IN, :product_quantity_IN, NOW(), :user_id_IN)";
//             $statement = $this->database_connection->prepare($sql);
//             $statement->bindParam(":product_id_IN", $product_id_IN);
//             $statement->bindParam(":product_quantity_IN", $product_quantity_IN);
//             $statement->bindParam(":user_id_IN", $user_id_IN);

//             if(!$statement->execute()) {
//                 //KUNDE INTE LÄGGA TILL I VARUKORG
                
//                 //$error->message = "Could not create product";
//                 //$error->code = "0007";
//                 //print_r(json_encode($error));
//                 //die();
//             }

//             //$this->productname = $product_name_IN;
//             //$this->description = $product_description_IN;
//             //$this->price = $product_price_IN;
            
//             //echo "Product created. Product name: $this->productname, Description: $this->description, Price: $this->price";
//             //die();
            
//             echo "Product added";

//         } else {
//             $error->message = "All arguments needs a value";
//             $error->code = "0004";
//             print_r(json_encode($error));
//             die();
//         }
//     }
// }
?>
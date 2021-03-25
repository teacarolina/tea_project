<?php 

//vilka private $ ska vara med? 
class Products {
    private $database_connection;
    private $product_id; 
    private $product_name; 
    private $product_description;
    private $product_price; 

    function __construct($db) {
        $this->database_connection = $db;
    }

    function createProduct($product_name_IN, $product_description_IN, $product_price_IN) {

        //OBS! kan man lägga den här utanför så alla når samma?
        $error = new stdClass();
        if(!empty($product_name_IN) && !empty($product_description_IN) && !empty($product_price_IN)) {

            $sql = "SELECT id FROM products WHERE ProductName = :product_name_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":product_name_IN", $product_name_IN);

            if(!$statement->execute()) {
                $error->message = "Could not execute query";
                $error->code = "0001";
                print_r(json_encode($error));
                die();   
            }

            $number_of_rows = $statement->rowCount();
            if($number_of_rows > 0) {
                $error->message = "The product is already registered";
                $error->code = "0006";
                print_r(json_encode($error));
                die();
            }

            $sql = "INSERT INTO products (ProductName, description, price) VALUES (:product_name_IN, :product_description_IN, :product_price_IN)";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":product_name_IN", $product_name_IN);
            $statement->bindParam(":product_description_IN", $product_description_IN);
            $statement->bindParam(":product_price_IN", $product_price_IN);

            if(!$statement->execute()) {
                $error->message = "Could not create product";
                $error->code = "0007";
                print_r(json_encode($error));
                die();
            }

            $this->productname = $product_name_IN;
            $this->description = $product_description_IN;
            $this->price = $product_price_IN;
            
            echo "Product created. Product name: $this->productname, Description: $this->description, Price: $this->price";
            die();
        
        } else {
            $error->message = "All arguments needs a value";
            $error->code = "0004";
            print_r(json_encode($error));
            die();
        }
    }

    function getAllProducts() {
        $sql = "SELECT id, ProductName FROM products";
        $statement = $this->database_connection->prepare($sql);
        $statement->execute();
        echo "<pre>";
        print_r(json_encode($statement->fetchAll(PDO::FETCH_ASSOC)));
        echo "</pre>";
    }

    function deleteProduct($product_id_IN) {

        $error = new stdClass();
        if(!empty($product_id_IN)) {
        $sql = "DELETE FROM products WHERE id = :product_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":product_id_IN", $product_id_IN);
        $statement->execute();

        $number_of_rows = $statement->rowCount();
          
        if($number_of_rows < 1) {
            $error->message = "Product doesn't exist";
            $error->code = "0008";
            print_r(json_encode($error));
            die();   
        }

        $this->id = $product_id_IN;

        echo "Product with id $this->id deleted.";
        die();

    } else {
        $error->message = "Product id is not specified";
        $error->code = "0009";
        print_r(json_encode($error));
        die();
        }
    }

    function updateProduct($product_id_IN, $product_name_IN = "", $product_description_IN = "", $product_price_IN = "") {
        
        $error = new stdClass();
        if(!empty($product_id_IN)) {

        if(!empty($product_name_IN)) {
            $this->updateProductName($product_id_IN, $product_name_IN);
        } 

        if(!empty($product_description_IN)) {
            $this->updateProductDescription($product_id_IN, $product_description_IN);
        }

        if(!empty($product_price_IN)) {
            $this->updateProductPrice($product_id_IN, $product_price_IN);
        }

        $this->id = $product_id_IN;

        echo "Product with id $this->id updated.";
        die();

    } else {
        $error->message = "Product id is not specified";
        $error->code = "0009";
        print_r(json_encode($error));
        die();
        }
    }

    function updateProductName($product_id_IN, $product_name_IN) {

        $error = new stdClass();
        $sql = "UPDATE products SET ProductName = :product_name_IN WHERE id = :product_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":product_id_IN", $product_id_IN);
        $statement->bindParam(":product_name_IN", $product_name_IN);
        $statement->execute();

        $number_of_rows = $statement->rowCount();
          
        if($number_of_rows < 1) {
            $error->message = "Product doesn't exist";
            $error->code = "0008";
            print_r(json_encode($error));
            die();   
        }
    }

    function updateProductDescription($product_id_IN, $product_description_IN) {

        $error = new stdClass();
        $sql = "UPDATE products SET description = :product_description_IN WHERE id = :product_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":product_id_IN", $product_id_IN);
        $statement->bindParam(":product_description_IN", $product_description_IN);
        $statement->execute();

        $number_of_rows = $statement->rowCount();
          
        if($number_of_rows < 1) {
            $error->message = "Product doesn't exist";
            $error->code = "0008";
            print_r(json_encode($error));
            die();   
        }
    }

    function updateProductPrice($product_id_IN, $product_price_IN) {

        $error = new stdClass();
        $sql = "UPDATE products SET price = :product_price_IN WHERE id = :product_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":product_id_IN", $product_id_IN);
        $statement->bindParam(":product_price_IN", $product_price_IN);
        $statement->execute();

        $number_of_rows = $statement->rowCount();
          
        if($number_of_rows < 1) {
            $error->message = "Product doesn't exist";
            $error->code = "0008";
            print_r(json_encode($error));
            die();   
        }
    }
}

?>
<?php

class Users {

    private $database_connection;
    private $username; 

    function __construct($db) {

        $this->database_connection = $db;
    }
                            
    function createUser($username_IN, $user_password_IN, $user_email_IN) {

        $error = new stdClass();   
        if(!empty($username_IN) && !empty($user_password_IN) && !empty($user_email_IN)) {

            $sql = "SELECT id FROM users WHERE username = :username_IN OR email = :user_email_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":user_email_IN", $user_email_IN);
            
            if(!$statement->execute()) {
                $error->message = "Could not execute query";
                $error->code = "0001";
                print_r(json_encode($error));
                die();   
            }

            $number_of_rows = $statement->rowCount();
            if($number_of_rows > 0) {
                $error->message = "The user is already registered";
                $error->code = "0002";
                print_r(json_encode($error));
                die();
            }
            //role is set as default "user" for security so role "admin" can only be set manually in db               
            $sql = "INSERT INTO users (username, password, email) VALUES (:username_IN, :user_password_IN, :user_email_IN)";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":user_password_IN", $user_password_IN);
            $statement->bindParam(":user_email_IN", $user_email_IN);

            if(!$statement->execute()) {
                $error->message = "Could not create user";
                $error->code = "0003";
                print_r(json_encode($error));
                die();
            }

            $this->username = $username_IN;
                                                   
            echo "User created: $this->username";
            die();
        
        } else {
            $error->message = "All arguments needs a value";
            $error->code = "0004";
            print_r(json_encode($error));
            die();
        }
    }

    function loginUser($username_IN, $user_password_IN) {

        $error = new stdClass();
        if(!empty($username_IN) && !empty($user_password_IN)) {
            
            $sql = "SELECT id, username, password, email, role FROM users WHERE username = :username_IN AND password = :user_password_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":user_password_IN", $user_password_IN);

            if(!$statement->execute()) {
                $error->message = "Could not execute query";
                $error->code = "0001";
                print_r(json_encode($error));
                die();   
            }
            //ny ovanför $statement->execute();
            $number_of_rows = $statement->rowCount();
            
            if($number_of_rows < 1) {
                $error->message = "Username or password is incorrect";
                $error->code = "0005";
                print_r(json_encode($error));
                die();   
            }

            $validate_token = $this->validateToken($username_IN);
            if(!empty($validate_token)) {
                echo "Already logged in";
                die();
            }
 
            while($row = $statement->fetch()) {
                $username_IN = $row['username'];
                $user_id_IN = $row['id'];
            }

            $token = md5(time() . $user_id_IN . $username_IN);  
                                                                            
            $sql = "INSERT INTO carts (TimeCreated, token, UserId) VALUES (:cart_time_created, :token_IN, :user_id_IN)";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":token_IN", $token);
            $statement->bindParam(":user_id_IN", $user_id_IN);
            $date = (new DateTime())->format('Y-m-d H:i:s');
       
            $statement->bindParam(":cart_time_created", $date);

            if(!$statement->execute()) {
                $error->message = "Could not create cart";
                $error->code = "0010";
                print_r(json_encode($error));
                die();
            }

            print_r("Session started for: " . $username_IN);
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
        if(!$statement->execute()) {
            $error->message = "Could not execute query";
            $error->code = "0001";
            print_r(json_encode($error));
            die();   
        }
        //ny ovanför $statement->execute();

        if($row = $statement->fetch()) {
            return $row['token'];
        } else {
            $sql = "SELECT token, TimeCreated FROM carts JOIN users ON carts.UserId = users.Id WHERE users.username = :username_IN AND TimeCreated < :active_time_IN";        
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
       
            $active_time = (new DateTime())->modify('-1 hours')->format('Y-m-d H:i:s');
     
            $statement->bindParam(":active_time_IN", $active_time);
            if(!$statement->execute()) {
                $error->message = "Could not execute query";
                $error->code = "0001";
                print_r(json_encode($error));
                die();   
            }
            //ny ovanför $statement->execute();

            if($row = $statement->fetch()) {
            $token = $row['token'];

            $sql = "DELETE FROM carts WHERE token = :token_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":token_IN", $token);
            if(!$statement->execute()) {
                $error->message = "Could not execute query";
                $error->code = "0001";
                print_r(json_encode($error));
                die();   
            }
            //ny ovanför $statement->execute();
            }
        } 
    }
}

?>
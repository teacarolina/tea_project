<?php

//vilka private $ ska vara med? 
class Users {
    private $database_connection;
    private $user_id; 
    private $username; 
    private $user_password;
    private $user_email; 
    private $token;
    //private $user_role; 

    function __construct($db) {
        $this->database_connection = $db;
    }
                            //$user_role_IN
    function createUser($username_IN, $user_password_IN, $user_email_IN) {

        //OBS! kan man lägga den här utanför så alla når samma?
        $error = new stdClass();    //&& !empty($user_role_IN)
        if(!empty($username_IN) && !empty($user_password_IN) && !empty($user_email_IN)) {

            $sql = "SELECT id FROM users WHERE username = :username_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            
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
                                                //role                                      //, :user_role_IN
            $sql = "INSERT INTO users (username, password, email) VALUES (:username_IN, :user_password_IN, :user_email_IN)";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":user_password_IN", $user_password_IN);
            $statement->bindParam(":user_email_IN", $user_email_IN);
            //$statement->bindParam(":user_role_IN", $user_role_IN);

            if(!$statement->execute()) {
                $error->message = "Could not create user";
                $error->code = "0003";
                print_r(json_encode($error));
                die();
            }

            $this->username = $username_IN;
            $this->password = $user_password_IN;
            $this->email = $user_email_IN;
            //$this->role = $user_role_IN;
                                                    //Role: $this->role
            echo "User created. Username: $this->username, Password: $this->password, Email: $this->email";
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

            $statement->execute();
            $number_of_rows = $statement->rowCount();
            
            if($number_of_rows < 1) {
                $error->message = "User doesn't exist";
                $error->code = "0005";
                print_r(json_encode($error));
                die();   
            }

            //vill jag ha den här koden här? eller ska det starta när man lägger något i varukorgen? 
            while($row = $statement->fetch()) {
                $username_IN = $row['username'];
                $user_id_IN = $row['id'];
            }

            $token = md5(time() . $user_id_IN . $username_IN);  
        
            $sql = "INSERT INTO carts (TimeCreated, token, UserId) VALUES (NOW(), :token_IN, :user_id_IN)";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":token_IN", $token);
            $statement->bindParam(":user_id_IN", $user_id_IN);
        
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
}

?>
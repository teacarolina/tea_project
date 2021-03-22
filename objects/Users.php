<?php

//vilka private $ ska vara med? 
class Users {
    private $database_connection;
    private $user_id; 
    private $username; 
    private $user_password;
    private $user_email; 
    private $user_role; 

    function __construct($db) {
        $this->database_connection = $db;
    }

    function createUser($username_IN, $user_password_IN, $user_email_IN, $user_role_IN) {

        //OBS! kan man lägga den här utanför så alla når samma?
        $error = new stdClass();
        if(!empty($username_IN) && !empty($user_password_IN) && !empty($user_email_IN) && !empty($user_role_IN)) {

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

            $sql = "INSERT INTO users (username, password, email, role) VALUES (:username_IN, :user_password_IN, :user_email_IN, :user_role_IN)";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":user_password_IN", $user_password_IN);
            $statement->bindParam(":user_email_IN", $user_email_IN);
            $statement->bindParam(":user_role_IN", $user_role_IN);

            if(!$statement->execute()) {
                $error->message = "Could not create user";
                $error->code = "0003";
                print_r(json_encode($error));
                die();
            }

            $this->username = $username_IN;
            $this->password = $user_password_IN;
            $this->email = $user_email_IN;
            $this->role = $user_role_IN;
            
            echo "User created. Username: $this->username, Password: $this->password, Email: $this->email, Role: $this->role";
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
        
            while($row = $statement->fetch()) {
            session_start();
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            }

            print_r("Session started for: " . $_SESSION['username']);
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
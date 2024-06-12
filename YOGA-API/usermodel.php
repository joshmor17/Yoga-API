<?php

class Users{
 
    private $conn;
    private $table_name = "yoga_accounts";
 
    public $id;
    public $email;
    public $password;
    public $firstname;
    public $midname;
    public $lastname;
    public $expiration;

    public $newpassword;
 
    public function __construct($db){
        $this->conn = $db;
    }
    

    function read()
    {
        $query = "SELECT * FROM yoga_accounts";
 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
 
        return $stmt;
    }

    function registeruser(){

        $isExisting = $this->checkIfUserExists($this->email);

        if($isExisting)
        {
            return false;
        }
        else
        {
            $query = "INSERT INTO yoga_accounts SET
            email=:email, password=:password, firstname=:firstname, midname=:midname, lastname=:lastname, expiration=:expiration";

            $stmt = $this->conn->prepare($query);

            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->password=htmlspecialchars(strip_tags($this->password));
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->midname=htmlspecialchars(strip_tags($this->midname));
            $this->lastname=htmlspecialchars(strip_tags($this->lastname));
            $this->expiration=htmlspecialchars(strip_tags($this->expiration));

            // bind values
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":firstname", $this->firstname);
            $stmt->bindParam(":midname", $this->midname);
            $stmt->bindParam(":lastname", $this->lastname);
            $stmt->bindParam(":expiration", $this->expiration);

            // execute query
            if($stmt->execute()){
                return true;
            }

            return false;
        }
    }

    function checkIfUserExists($email)
    {
        $query = "SELECT * FROM yoga_accounts WHERE email=:email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $num = $stmt->rowCount();

        if($num > 0)
        {
            return true;
        }
        return false;
    }

    function userlogin(){
        $query = "SELECT * FROM yoga_accounts WHERE email=:email AND password=:password";
     
        $stmt = $this->conn->prepare($query);
     
        
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
     
        $stmt->execute();
        $num = $stmt->rowCount();

        if($num > 0)
        {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
            $this->email = $row['email'];
            $this->firstname = $row['firstname'];
            $this->midname = $row['midname'];
            $this->lastname = $row['lastname'];
            $this->expiration = $row['expiration'];
        }
    }

    function changePassword(){
        $isOldPasswordCorrect = $this->checkOldPassword($this->email, $this->password);

        if(!$isOldPasswordCorrect)
        {
            return false;
        }
        else
        {
            $query = "UPDATE yoga_accounts SET
                      password=:newpassword WHERE email=:email";

            $stmt = $this->conn->prepare($query);

            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->newpassword=htmlspecialchars(strip_tags($this->newpassword));

            // bind values
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":newpassword", $this->newpassword);

            // execute query
            if($stmt->execute()){
                return true;
            }

            return false;
        }
    }

    function checkOldPassword($email, $password)
    {
        $query = "SELECT * FROM yoga_accounts WHERE email=:email AND password=:password";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $this->password);
        $stmt->execute();
        $num = $stmt->rowCount();

        if($num > 0)
        {
            return true;
        }
        return false;
    }
}
?>
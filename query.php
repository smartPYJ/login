<?php
// 'user' object
class User{

    // database connection and table name
    private $conn;
    private $table_name = "user";

    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $phone;
    public $location;
    public $username;
    public $sex;


    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // create new user record
    function create(){

        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                     firstname = :firstname,
                     lastname = :lastname,
                     email = :email,
                     password = :password,
                     phone = :phone,
                     location =  :location,
                     username = :username,
                     sex = :sex";


        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->sex=htmlspecialchars(strip_tags($this->sex));

        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':sex', $this->sex);




        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // check if given email exist in the database
    function emailExists(){

        // query to check if email exists
        $query = "SELECT id, firstname, lastname, password
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare( $query );

        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email)) ;


        // bind given email value
        $stmt->bindParam(1, $this->email);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();


        if($num>0){


            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];


            return true;
        }


        return false;
    }

  }

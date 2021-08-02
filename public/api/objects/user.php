<?php

class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $created_at;
    public $updated_at;
 
    // constructor with $db as database connection
    public function __construct($db){
        
        $this->conn = $db;
        
    }
    // signup user
    function signup(){
        $put = $this->isEmptyFields();
        if ($put != 1) {
            return $put;
        }
       if($this->isAlreadyExist()){
            return false;
        }

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET first_name=:first_name,last_name=:last_name,email=:email, password=:password, created_at=:created_at,updated_at=:updated_at";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->first_name=htmlspecialchars(strip_tags($this->first_name));
        $this->last_name=htmlspecialchars(strip_tags($this->last_name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        // bind values
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

       
        $date = date('Y-m-d H:i:s');
        $code = $this->generateRandomString(32);
        
        try{
            if($stmt->execute()){
                $this->id = $this->conn->lastInsertId();
                //store activation code in activation table
                $user_actv_qry = "INSERT INTO activations (user_id,code,completed,completed_at,created_at,updated_at) VALUES (?,?,?,?,?,?)";
                $user_actv_stmt = $this->conn->prepare($user_actv_qry);
                $user_actv_stmt->execute([$this->id,$code,1,$date,$date,$date]);
                //store user role in user_role table
                $user_role_qry = "INSERT INTO user_roles (user_id,role_id,created_at,updated_at) VALUES (?,?,?,?)";
                $user_rol_stmt = $this->conn->prepare($user_role_qry);
                $user_rol_stmt->execute([$this->id,2,$date,$date]);
            }else{
                $this->id = false;
            }
        }catch(Exception $e){
            echo $e->getMessae();
            exit();
        }
        return $this->id;
        
    }
    // login user
    function login(){
        if ($put = $this->isEmptyFieldsforLogin()) {
            if($put != 1){
                echo $put;
                exit('here');
            }
        }
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " 
                WHERE
                    email='".$this->email."'";//" AND password='".$this->password."'";
        // prepare query statement
        //print_r($query); die();
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result && password_verify($this->password, $result['password'])){
            return $result;
        }else{
            //Invalid Email or Password!
            echo json_encode(array(
                'status' => 0,
                'message' => 'Correo electrónico o contraseña no válidos'
            ));
            exit();
        }

    }
    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                email='".$this->email."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function isEmptyFields()
    {
        if ($this->first_name != "" && $this->last_name != "" && $this->email != "" && $this->password != "") {
            return true;
        }else{
            //Missing Information!
            echo json_encode(array(
                'status' => 0,
                'message' => 'Falta información!'
            ));
            exit();
        }
    }
    function isEmptyFieldsforLogin()
    {
        if ($this->email != "" && $this->password != "") {
            return true;
        }else{
            //Missing Information!
            echo json_encode(array(
                'status' => 0,
                'message' => 'Falta información!'
            ));
            exit();
        }
    }

     function generateRandomString($length = 32) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
}
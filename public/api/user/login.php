<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);
// set ID property of user to be edited
if(isset($_POST['email'])){
    $user->email = $_POST['email'];
}
if(isset($_POST['password'])){
    $user->password = $_POST['password'];

}

// read the details of user to be edited
$stmt = $user->login();

if($stmt){
    // get retrieved row
    //$row = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($row); die();
    // create array
    //Successfully Login!
    $user_arr=array(
        "status" => 1,
        "message" => "¡Ingrese exitosamente!",
        "data" => $stmt
    );
}
else{
    $user_arr = $user->login();
    /*$user_arr=array(
        "status" => 0,
        "message" => "Invalid email or Password!",
    );*/
}
// make it json format
print_r(json_encode($user_arr,JSON_PRETTY_PRINT));
?>
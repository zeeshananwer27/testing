<?php
 
// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// set user property values
if(isset($_POST['email'])){
    $user->email = $_POST['email'];
}
if(isset($_POST['password'])){
    $user->password = $_POST['password'];

}
$user->created_at = date('Y-m-d H:i:s',time());
$user->updated_at = date('Y-m-d H:i:s',time());
if(isset($_POST['first_name'])){
    $user->first_name = $_POST['first_name'];
}
if(isset($_POST['last_name'])){
    $user->last_name = $_POST['last_name'];
}
 
// create the user
if($user->signup()){
    //Successfully Signup!
    $user_arr=array(
        "status" => 1,
        "message" => "Regístrese con éxito!",
        "id" => $user->id,
        "data" => $user
    );
}
else{
    //email already exists!
    $user_arr=array(
        "status" => 0,
        "message" => "¡el Email ya existe!"
    );
}
print_r(json_encode($user_arr));
?>
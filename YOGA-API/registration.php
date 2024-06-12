<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/usermodel.php';
 
$database = new Database();
$db = $database->getConnection();
 
$users = new Users($db);
 
$data = json_decode(file_get_contents("php://input"));
 
if(
    !empty($data->email) &&
    !empty($data->password) &&
    !empty($data->firstname) &&
    !empty($data->midname) &&
    !empty($data->lastname) &&
    !empty($data->expiration))
    {

    $users->email = $data->email;
    $users->password = $data->password;
    $users->firstname = $data->firstname;
    $users->midname = $data->midname;
    $users->lastname = $data->lastname;
    $users->expiration = $data->expiration;
 
    if($users->registeruser()){

        http_response_code(200);
        echo json_encode(array("message" => "Success"));
    }
 
    else{
        http_response_code(400);
        echo json_encode(array("message" => "Registration failed. Email is already used."));
    }
}
else{

    http_response_code(400);
    echo json_encode(array("message" => "Unable to register user. Data is incomplete."));
}
?>
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
    !empty($data->newpassword))
    {

    $users->email = $data->email;
    $users->password = $data->password;
    $users->newpassword = $data->newpassword;
 
    if($users->changePassword()){

        http_response_code(200);
        echo json_encode(array("message" => "Success"));
    }
 
    else{
        http_response_code(400);
        echo json_encode(array("message" => "Change password failed. Old password is incorrect"));
    }
}
else{

    http_response_code(400);
    echo json_encode(array("message" => "Change password failed. Data is incomplete."));
}
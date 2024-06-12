<?php
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

$users->email = $data->email;
$users->password = $data->password;

$stmt = $users->userlogin();
 
if($users->email != null 
    && $users->password !=null 
    && $users->firstname !=null
    && $users->midname !=null
    && $users->lastname !=null
    )
    {
    $user_arr = array(
        "email" => $users->email,
        "firstname" => $users->firstname,
        "lastname" => $users->lastname,
        "expiration" => $users->expiration
 
    );
    http_response_code(200);
    echo json_encode($user_arr);
}
 
else{
    http_response_code(404);
    echo json_encode(array("message" => "Username/password is incorrect"));
}
?>
<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/usermodel.php';

$database = new Database();
$db = $database->getConnection();

$users = new Users($db);

$stmt = $users->read();
$num = $stmt->rowCount();

if($num>0){
 
    $users_arr=array();
    $users_arr["useraccounts"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $users_result=array(
            "id" => $id,
            "email" => $email,
            "firstname" => $firstname,
            "midname" => $midname,
            "lastname" => $lastname,
            "expiration" => $expiration
        );
 
        array_push($users_arr["useraccounts"], $users_result);
    }
 
    http_response_code(200);
    
    echo json_encode($users_arr);
}
else{

    http_response_code(404);
 
    echo json_encode(
        array("message" => "No users found.")
    );
}

?>
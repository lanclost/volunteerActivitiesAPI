<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once ('../../../models/UserModel.php'); 
session_start();

$user_model = new UserModel;  


$json = file_get_contents('php://input');

 // decoding the received JSON and store into $request variable.
$request = json_decode($json,true);
$response_data=[];
if (isset($request['user_id'])) {
	
    $user_id = $request['user_id'];  
  
    $output = $user_model->deleteUserByID($user_id); 
    
    $response_data ['data'] = $request ;
    $response_data ['require'] = $output;
} else {
   $response_data ['require'] = false;
} 
echo json_encode($response_data);

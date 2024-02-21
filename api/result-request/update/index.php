<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once ('../../../models/ResultRequestModel.php'); 
// session_start();

$resultrequest_model = new ResultRequestModel;  

$json = file_get_contents('php://input');

 // decoding the received JSON and store into $request variable.
$request = json_decode($json,true);
if (isset($request['result_request_id'])) {
	  
    $resultrequest = $resultrequest_model->updateResultRequestBy($request['result_request_id'],$request);  
    if($resultrequest){ 
        $response_data ['resultrequest'] = $resultrequest;
        $response_data ['require'] = true;
    }else{ 
        $response_data ['resultrequest'] =  $request;
        $response_data ['require'] = true;
    }
} else {
   $response_data ['require'] = false;
} 
echo json_encode($response_data);

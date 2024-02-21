<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once ('../../../models/SendEvidenceModel.php'); 
// session_start();

$sendevidence_model = new SendEvidenceModel;  

$json = file_get_contents('php://input');

 // decoding the received JSON and store into $request variable.
$request = json_decode($json,true);
if (isset($request['evidence_id'])) {
	  
    $sendevidence = $sendevidence_model->updateSendEvidenceBy($request['evidence_id'],$request);  
    if($sendevidence){ 
        $response_data ['sendevidence'] = $sendevidence;
        $response_data ['require'] = true;
    }else{ 
        $response_data ['sendevidence'] =  $request;
        $response_data ['require'] = true;
    }
} else {
   $response_data ['require'] = false;
} 
echo json_encode($response_data);

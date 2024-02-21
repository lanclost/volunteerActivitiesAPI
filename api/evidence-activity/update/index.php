<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once ('../../../models/EvidenceActivityModel.php'); 
session_start();

$evidenceactivity_model = new EvidenceActivityModel;  

$json = file_get_contents('php://input');

 // decoding the received JSON and store into $request variable.
$request = json_decode($json,true);
if (isset($request['evidence_id'])) {
	  
    $evidenceactivity = $evidenceactivity_model->updateEvidenceActivityBy($request['evidence_id'],$request);  
    if($evidenceactivity){ 
        $response_data ['evidenceactivity'] = $evidenceactivity;
        $response_data ['require'] = true;
    }else{ 
        $response_data ['evidenceactivity'] =  $request;
        $response_data ['require'] = true;
    }
} else {
   $response_data ['require'] = false;
} 
echo json_encode($response_data);

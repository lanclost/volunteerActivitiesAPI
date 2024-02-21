<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once ('../../../models/ActivityListModel.php'); 
session_start();

$activitylist_model = new ActivityListModel;  

$json = file_get_contents('php://input');

 // decoding the received JSON and store into $request variable.
$request = json_decode($json,true);
if (isset($request['activity_list_id'])) {
	  
    $data = $activitylist_model->updateActivityListBy($request);  
    if($data){
        $response_data['data'] =$data;
        $response_data['require'] = true;
        $response_data['message'] ='สำเร็จ';
    
    }else{
        $response_data['data'] =[];
        $response_data['require'] = false;
        $response_data['message'] ='ผิดพลาด';
    }
} else {
   $response_data ['require'] = false;
} 
echo json_encode($response_data);

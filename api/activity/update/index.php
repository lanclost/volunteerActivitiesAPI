<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once ('../../../models/ActivityModel.php'); 
require_once('../../../models/ActivityListModel.php');
session_start();

$activity_model = new ActivityModel;  
$activitylist_model = new ActivityListModel;
$json = file_get_contents('php://input');

 // decoding the received JSON and store into $request variable.
$request = json_decode($json,true);
if (isset($request['ac_id'])) {
    $data = $activity_model->updateActivityBy($request);  
    if($data){
        $response_data['data'] =$data;
        $response_data['require'] = true;
        $response_data['message'] ='สำเร็จ';
    
    }else{
        $response_data['data'] =$data;
        $response_data['require'] = false;
        $response_data['message'] ='ผิดพลาด';
    }
}else if ($request["action"] == "updateActivityByApprove") {
 
    $data = $user_model->updateActivityByApprove($request);
    if($data){
        $response_data['data'] =$data;
        $response_data['require'] = true;
        $response_data['message'] ='สำเร็จ';
    
    }else{
        $response_data['data'] =$data;
        $response_data['require'] = false;
        $response_data['message'] ='ผิดพลาด';
    }

} 
echo json_encode($response_data);

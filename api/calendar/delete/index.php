<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once ('../../../models/CalendarModel.php'); 
session_start();

$calendar_model = new CalendarModel;  


$json = file_get_contents('php://input');

 // decoding the received JSON and store into $request variable.
$request = json_decode($json,true);
$response_data=[];
if (isset($request['calendar_id'])) {
  
    $data = $calendar_model->deleteCalendarByID($request); 
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

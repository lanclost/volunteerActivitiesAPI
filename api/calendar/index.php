<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// require_once("../../src/token.php");

// session_start();
require_once('../../models/CalendarModel.php');
$calendar_model = new CalendarModel;

$json = file_get_contents('php://input');
$request = json_decode($json, true);

$response_data = [];


if ($request["action"] == "getCalendarBy") {

    $data = $calendar_model->getCalendarBy($request);
    if($data){
        $response_data['data'] =$data;
        $response_data['require'] = true;
        $response_data['message'] ='สำเร็จ';
    
    }else{
        $response_data['data'] =[];
        $response_data['require'] = false;
        $response_data['message'] ='ผิดพลาด';
    }
} else if ($request["action"] == "getCalendarByID") {

    $data = $calendar_model->getCalendarByID($request);
    if($data){
        $response_data['data'] =$data;
        $response_data['require'] = true;
        $response_data['message'] ='สำเร็จ';
    
    }else{
        $response_data['data'] =[];
        $response_data['require'] = false;
        $response_data['message'] ='ผิดพลาด';
    }
} else if ($request["action"] == "getCalendarLastID") {

    $data = $calendar_model->getCalendarLastID($request);
    if($data){
        $response_data['data'] =$data;
        $response_data['require'] = true;
        $response_data['message'] ='สำเร็จ';
    
    }else{
        $response_data['data'] =[];
        $response_data['require'] = false;
        $response_data['message'] ='ผิดพลาด';
    }
}

echo json_encode($response_data);

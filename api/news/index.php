<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// require_once("../../src/token.php");

// session_start();
require_once('../../models/NewsModel.php');
$news_Model = new NewsModel;

$json = file_get_contents('php://input');
$request = json_decode($json, true);

$response_data = [];
// if (isset($request["action"]) == "getNewsBy") {
//     $sendevidence = $news_Model->getNewsBy($request);  
// }
if ($request["action"] == "getNewsBy") {

    $data = $news_Model->getNewsBy($request);
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

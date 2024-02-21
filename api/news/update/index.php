<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once ('../../../models/NewsModel.php'); 
// session_start();

$news_Model = new NewsModel;  

$json = file_get_contents('php://input');

 // decoding the received JSON and store into $request variable.
$request = json_decode($json,true);
if (isset($request['news_id'])) {
	  
    $news = $news_Model->updateNewsBy($request['news_id'],$request);  
    if($news){ 
        $response_data ['news'] = $news;
        $response_data ['require'] = true;
    }else{ 
        $response_data ['news'] =  $request;
        $response_data ['require'] = true;
    }
} else {
   $response_data ['require'] = false;
} 
echo json_encode($response_data);

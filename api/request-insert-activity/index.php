<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// require_once("../../src/token.php");

// session_start();
require_once('../../models/RequestInsertActivityModel.php');
$requestinsertactivity_model = new RequestInsertActivityModel;

$json = file_get_contents('php://input');
$request = json_decode($json, true);

$response_data = [];


if (isset($request["action"]) == "getRequestInsertActivityBy") {

    $resultrequest = $requestinsertactivity_model->getRequestInsertActivityBy($request);
}





echo json_encode($resultrequest);

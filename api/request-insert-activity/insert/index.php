<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once('../../../models/RequestInsertActivityModel.php');
// session_start();

$requestinsertactivity_model = new RequestInsertActivityModel;

$json = file_get_contents('php://input');

// decoding the received JSON and store into $request variable.
$request = json_decode($json, true);
if (isset($request['insert_requestinsertactivity'])) {
    $requestinsertactivity = $requestinsertactivity_model->insertRequestInsertActivity($request);
    if ($requestinsertactivity) {
        $response_data['requestinsertactivity'] = $requestinsertactivity;
        $response_data['require'] = true;
    } else {
        $response_data['requestinsertactivity'] =  $request;
        $response_data['require'] = true;
    }
} else {
    $response_data['data'] = $request;
    $response_data['require'] = false;
}
echo json_encode($response_data);

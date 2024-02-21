<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// require_once("../../src/token.php");

// session_start();
require_once('../../models/SendEvidenceModel.php');
$sendevidence_Model = new SendEvidenceModel;

$json = file_get_contents('php://input');
$request = json_decode($json, true);

$response_data = [];


if (isset($request["action"]) == "getSendEvidenceBy") {

    $sendevidence = $sendevidence_Model->getSendEvidenceBy($request);
}





echo json_encode($sendevidence);

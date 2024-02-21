<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
$uploadDir = 'volunteering_activities_API/uploads/';
$newFileName = $_FILES['file']['name']; // Change the logic here to modify the file name

$uploadPath = $uploadDir . $newFileName;
if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
    if($uploadPath){
        $response_data['data'] =$uploadPath;
        $response_data['require'] = true;
        $response_data['message'] ='สำเร็จ';
    
    }else{
        $response_data['data'] =$uploadPath;
        $response_data['require'] = false;
        $response_data['message'] ='ผิดพลาด';
    }
} else {
    $response_data['data'] = $uploadPath;
    $response_data['require'] = false;
    $response_data['message'] ='ผิดพลาด';
}

echo json_encode($response_data);

?>

<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$response_data = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['evidence_file'];
    $filePath = 'volunteering_activities_API/uploads/' . $filename;

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            $response_data['data'] = $filePath;
            $response_data['require'] = true;
            $response_data['message'] = 'ลบไฟล์สำเร็จ';
        } else {
            $response_data['data'] = $filePath;
            $response_data['require'] = false;
            $response_data['message'] = 'เกิดข้อผิดพลาดในการลบไฟล์';
        }
    } else {
        $response_data['data'] = $filePath;
        $response_data['require'] = false;
        $response_data['message'] = 'ไม่พอไฟล์';
    }
}

echo json_encode($response_data);
?>

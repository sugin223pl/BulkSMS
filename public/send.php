<?php
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
include './vendor/autoload.php';
use \Httpful\Request as Request;

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
echo $contentType;
exit;

$data = json_decode(file_get_contents('php://input'), true);
    //print_r($_POST);
    echo json_encode($data);
    exit;
if(isset($_POST)) {
    
    echo json_encode($_POST);
} else {
    $uri = "http://192.168.1.14:1688/services/api/status";
    $response = Request::get($uri)
        ->expectsJson()
        ->sendIt();
    // Headers for CORS
    
    //echo json_encode($response->body);
    echo 'MUIE';
}

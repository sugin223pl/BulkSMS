<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include '../../vendor/autoload.php';
use \Httpful\Request as Request;

$data = json_decode(file_get_contents('php://input'));
$server = $data->server;
$data->to = (int) $data->to;
unset($data->server);

$response = \Httpful\Request::post($server)
->body((array) $data, Httpful\Mime::FORM)
->expectsJson()
->send();
echo json_encode($response->body);

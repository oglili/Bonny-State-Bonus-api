<?php

include_once '../../database/DbConnection.php';
include_once '../../models/Service.php';
$config = include_once '../../../bonny_config/config.php';

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Max-Age: 3600');
header(
    'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
);

$db = DbConnection::make($config);
$bonus_service = new Service($db);
$data = json_decode(file_get_contents('php://input'));

if (!empty($data->id)) {
    $bonus_service->id = $data->id;
    if ($bonus_service->delete()) {
        $resp = [
            'status_code' => 200,
            'message' => 'Bonus deleted successfully!',
        ];
        echo json_encode($resp);
    } else {
        $resp = [
            'status_code' => 503,
            'message' => 'Unable to delete bonus',
        ];
        echo json_encode($resp);
    }
} else {
    $resp = [
        'status_code' => 400,
        'message' => "Bad Request: missing 'id' parameter",
    ];
    echo json_encode($resp);
}

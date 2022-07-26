<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header(
    'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
);

include_once '../../database/DbConnection.php';
include_once '../../models/Type.php';
$config = include_once '../../../bonny_config/config.php';

$db = DbConnection::make($config);
$bonus_type = new Type($db);
$data = json_decode(file_get_contents('php://input'));

if (!empty($data->type) && !empty($data->saved_minutes)) {
    $bonus_type->type = $data->type;
    $bonus_type->saved_minutes = $data->saved_minutes;

    if ($bonus_type->create()) {
        $resp = ['status_code' => 201, 'message' => 'Created successfully!'];
        echo json_encode($resp);
    } else {
        $resp = [
            'status_code' => 503,
            'message' => 'Unable to create bonus type',
        ];
        echo json_encode($resp);
    }
} else {
    $resp = ['status_code' => 400, 'message' => 'Incomplete data'];
    echo json_encode($resp);
}

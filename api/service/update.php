<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Max-Age: 3600');
header(
    'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
);

include_once '../../database/DbConnection.php';
include_once '../../models/Service.php';
$config = include_once '../../../bonny_config/config.php';

$db = DbConnection::make($config);
$bonus_service = new Service($db);
$data = json_decode(file_get_contents('php://input'));

if (
    !empty($data->id) &&
    !empty($data->type_id) &&
    !empty($data->quantity) &&
    !empty($data->sold_at)
) {
    $bonus_service->id = $data->id;
    $bonus_service->name = $data->name;
    $bonus_service->type_id = $data->type_id;
    $bonus_service->quantity = $data->quantity;
    $bonus_service->sold_at = $data->sold_at;

    if ($bonus_service->update()) {
        $resp = ['status_code' => 201, 'message' => 'Updated successfully!'];
        echo json_encode($resp);
    } else {
        $resp = [
            'status_code' => 503,
            'message' => 'Unable to update bonus',
        ];
        echo json_encode($resp);
    }
} else {
    $resp = ['status_code' => 400, 'message' => 'Incomplete data'];
    echo json_encode($resp);
}

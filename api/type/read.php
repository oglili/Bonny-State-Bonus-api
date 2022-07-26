<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json; charset=UTF-8');

include_once '../../database/DbConnection.php';
include_once '../../models/Type.php';
$config = include_once '../../../bonny_config/config.php';

$db = DbConnection::make($config);
$bonus_type = new Type($db);

$stmt = $bonus_type->read();
$numCount = $stmt->rowCount();

if ($numCount > 0) {
    $bonus_type_arr = [];
    $bonus_type_arr['status_code'] = 200;
    $bonus_type_arr['body'] = [];
    $bonus_type_arr['numCount'] = $numCount;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $e = [
            'id' => $id,
            'type' => $type,
            'saved_minutes' => $saved_minutes,
        ];
        array_push($bonus_type_arr['body'], $e);
    }
    echo json_encode($bonus_type_arr);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'No record found.']);
}

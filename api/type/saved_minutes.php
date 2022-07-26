<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json; charset=UTF-8');

include_once '../../database/DbConnection.php';
include_once '../../models/Type.php';
$config = include_once '../../../bonny_config/config.php';

$db = DbConnection::make($config);
$bonus_type = new Type($db);

$stmt = $bonus_type->read_saved_minutes();
$numCount = $stmt->rowCount();

if ($numCount > 0) {
    http_response_code(200);
    // array di libri
    $bonus_type_arr = [];
    $bonus_type_arr['status_code'] = 200;
    $bonus_type_arr['body'] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $bonus_type_new = [
            'total saved minutes' => $total_saved_minutes,
        ];
        array_push($bonus_type_arr['body'], $bonus_type_new);
    }
    echo json_encode($bonus_type_arr);
} else {
    $resp = [
        'body' => null,
        'count' => 0,
    ];
    print json_encode($resp);
}

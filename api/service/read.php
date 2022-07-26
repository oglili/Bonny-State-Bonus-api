<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json; charset=UTF-8');

include_once '../../database/DbConnection.php';
include_once '../../models/Service.php';
$config = include_once '../../../bonny_config/config.php';

$db = DbConnection::make($config);
$bonus_service = new Service($db);

$types = isset($_GET['types']) ? $_GET['types'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

$params = [
    'types' => $types,
    'from_date' => $from_date,
    'to_date' => $to_date,
];

$stmt = $bonus_service->read($params);
$numCount = $stmt->rowCount();

if ($numCount > 0) {
    http_response_code(200);
    // array
    $bonus_service_arr = [];
    $bonus_service_arr['status_code'] = 200;
    $bonus_service_arr['body'] = [];
    $bonus_service_arr['numCount'] = $numCount;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $e = [
            'id' => $id,
            'name' => $name,
            'type' => $type,
            'quantity' => $quantity,
            'sold_at' => $sold_at,
        ];
        array_push($bonus_service_arr['body'], $e);
    }
    echo json_encode($bonus_service_arr);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'No record found.']);
}

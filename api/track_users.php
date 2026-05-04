<?php
session_start();
header('Content-Type: application/json');
$user = $_SESSION['user'];

$body = json_decode(file_get_contents('php://input'), true);
$id = $body['room_id'];
$file = "../data/rooms/$id.json";
$room = json_decode(file_get_contents($file), true);

//add user timestamp 
$time = $room['viewers_ts'] ?? [];
$time[$user] = time();

//15 sec lapse with no activity > remove from active user list
foreach ($time as $u => $ts) { if (time() - $ts > 15) unset($time[$u]); }
$room['viewers_ts'] = $time;
//viewer list for frontend
$room['viewers'] = array_keys($time);

file_put_contents($file, json_encode($room));
echo json_encode(['ok' => true]);

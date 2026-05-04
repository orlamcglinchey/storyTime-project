<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user'])) {
   echo json_encode(['ok' => false, 'error' => 'not logged in']);
    exit; 
    }



$user = $_SESSION['user'];
$body = json_decode(file_get_contents('php://input'), true);
$id = $body['room_id'];
$text = trim($body['text']);
$file = "../data/rooms/$id.json";
$room = json_decode(file_get_contents($file), true);

if (!$text) {
  //prevent entering empty line of text
   echo json_encode(['ok' => false, 'error' => 'Empty line.']); 
   exit;
   
   }

   
//not allowed to write 2 lines in a row
$lines = $room['lines'];
if ($lines && end($lines)['author'] == $user) {

  echo json_encode(['ok' => false, 'error' => 'Wait for someone else to write a line.']); 
  exit;
}

$room['lines'][] = ['author' => $user, 'text' => $text];

file_put_contents($file, json_encode($room));


echo json_encode(['ok' => true]);

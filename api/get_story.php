<?php
session_start();
if (!isset($_SESSION['user'])) {
     echo json_encode(['error' => 'not logged in']);
      exit;
       }

header('Content-Type: application/json');
$id = $_GET['id'];
$file = "../data/rooms/$id.json";
$room = json_decode(file_get_contents($file), true);
echo json_encode(['lines' => $room['lines'], 'viewers' => $room['viewers'] ?? []]);//doesn't exist - show nothing

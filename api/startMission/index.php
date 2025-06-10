<?php

include '../../bot/config.php';
include '../../bot/functions.php';

$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) die;
function ToDie($MySQLi){
$MySQLi->close();
die;
}


session_start();
$app_hash = $_SESSION['app_hash'];

$get_user = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `users` WHERE `hash` = '{$app_hash}' LIMIT 1"));

if(!$get_user){
    http_response_code(300);
    echo json_encode(['ok' => false, 'message' => 'user not found'], JSON_PRETTY_PRINT);
    $MySQLi->close();
    die;
}

$missionId = json_decode(file_get_contents('php://input'), true)['missionId'];


$MySQLi->query("INSERT INTO `user_missions` (`user_id`, `mission_id`, `status`) VALUES ('{$get_user['id']}', '{$missionId}', '1')");


$MySQLi->close();
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


$MySQLi->query("UPDATE `user_missions` SET `status` = '2' WHERE `mission_id` = '{$missionId}' AND `user_id` = '{$get_user['id']}' LIMIT 1");

$reward = (int) mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `missions` WHERE `id` = '{$missionId}' LIMIT 1"))['reward'];

$MySQLi->query("UPDATE `users` SET  `balance` = `balance` + '{$reward}' WHERE `hash` = '{$app_hash}' LIMIT 1");




$MySQLi->close();
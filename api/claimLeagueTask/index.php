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
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'user not found'], JSON_PRETTY_PRINT);
    $MySQLi->close();
    die;
}


$league = json_decode(file_get_contents('php://input'), true)['league'];

if(mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `leaguesTasks` WHERE `user_id` = '{$get_user['id']}' AND `league` = '$league' LIMIT 1"))){
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'error'], JSON_PRETTY_PRINT);
    $MySQLi->close();
    die;
}


$league_prize = array(
    "bronze" => 1000,
    "silver" => 5000,
    "gold" => 10000,
    "platinum" => 30000,
    "diamond" => 50000,
    "master" => 100000,
    "grandmaster" => 250000,
    "elite" => 500000,
    "legendary" => 1000000
);

if(!is_numeric($league_prize[$league])){
    $MySQLi->close();
    die;
}


$newBalance = $get_user['balance'] + $league_prize[$league]?:0;
$MySQLi->query("UPDATE `users` SET `balance` = '{$newBalance}' WHERE `hash` = '{$app_hash}' LIMIT 1");

$MySQLi->query("INSERT INTO `leaguesTasks` (`user_id`, `league`) VALUES ('{$get_user['id']}', '{$league}')");


$MySQLi->close();
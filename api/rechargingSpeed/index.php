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

$rechargingSpeed = $get_user['rechargingSpeed'];
if($rechargingSpeed == 5){
    http_response_code(300);
    echo json_encode(['ok' => false, 'message' => 'error'], JSON_PRETTY_PRINT);
    $MySQLi->close();
    die;
}

$price_list =  [2000, 10000, 100000, 250000,500000,1000000,1250000,1500000];
$newBalance = $get_user['balance'] - $price_list[$rechargingSpeed-1];
$rechargingSpeed++;

$MySQLi->query("UPDATE `users` SET `rechargingSpeed` = '{$rechargingSpeed}' ,`balance` = '{$newBalance}' WHERE `hash` = '{$app_hash}' LIMIT 1");



$MySQLi->close();
// do rechargingSpeed level up to 5
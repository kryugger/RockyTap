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

$energyLimit = $get_user['energyLimit'];
if($energyLimit == 20){
    http_response_code(300);
    echo json_encode(['ok' => false, 'message' => 'error'], JSON_PRETTY_PRINT);
    $MySQLi->close();
    die;
}

$price_list =  [200, 500, 1000, 2000, 4000, 8000, 16000, 25000, 50000, 100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000, 1000000];
$newBalance = $get_user['balance'] - $price_list[$energyLimit-1];
$energyLimit++;

$MySQLi->query("UPDATE `users` SET `energyLimit` = '{$energyLimit}' ,`balance` = '{$newBalance}' WHERE `hash` = '{$app_hash}' LIMIT 1");



$MySQLi->close();
// do energyLimit level up to 20
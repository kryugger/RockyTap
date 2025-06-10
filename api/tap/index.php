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


//          calculate user energy           //
$remaining_time = time() - $get_user['lastTapTime'];
$calulated_energy = ($remaining_time * $get_user['rechargingSpeed']) + $get_user['energy'];
if($calulated_energy > $get_user['energyLimit'] * 500) $calulated_energy = $get_user['energyLimit'] * 500;
$MySQLi->query("UPDATE `users` SET `energy` = '{$calulated_energy}' WHERE `hash` = '{$app_hash}' LIMIT 1");
$get_user = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `users` WHERE `hash` = '{$app_hash}' LIMIT 1"));




$tapsInc = (int) json_decode(file_get_contents('php://input'), true)['tapsInc'];
$tappingGuruEnded = (int) json_decode(file_get_contents('php://input'), true)['tappingGuruEnded']?:false;


$time = time();
if($tappingGuruEnded == true){
    $tapsInc *= 5;
    $tapsInc = $tapsInc * $get_user['multitap'];
    $energy = $get_user['energy'];
    $tappingGuruStarted = (microtime(true) * 1000) + 20000;
    $MySQLi->query("UPDATE `users` SET `score` = `score` + '{$tapsInc}', `balance` = `balance` + '{$tapsInc}',`lastTapTime` = '{$time}',`tappingGuruStarted` = '{$time}' WHERE `hash` = '{$app_hash}' LIMIT 1");
}elseif((microtime(true) * 1000) - $get_user['tappingGuruStarted'] <= 20000){
    $tapsInc *= 5;
    $tapsInc = $tapsInc * $get_user['multitap'];
    $energy = $get_user['energy'];
    $MySQLi->query("UPDATE `users` SET `score` = `score` + '{$tapsInc}', `balance` = `balance` + '{$tapsInc}',`lastTapTime` = '{$time}' WHERE `hash` = '{$app_hash}' LIMIT 1");
}else{
$tapsInc = $tapsInc * $get_user['multitap'];

if($tapsInc > $get_user['energy']){
    $tapsInc = $get_user['energy'];
    $energy = 0;
}else{
    $energy = $get_user['energy'] - $tapsInc;
}

$MySQLi->query("UPDATE `users` SET `score` = `score` + '{$tapsInc}', `balance` = `balance` + '{$tapsInc}', `energy` = '{$energy}',`lastTapTime` = '{$time}' WHERE `hash` = '{$app_hash}' LIMIT 1");
}

$get_user = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `users` WHERE `hash` = '{$app_hash}' LIMIT 1"));

$data = array(
    "score" => $get_user['score'],
    "balance" => $get_user['balance'],
    "energy" => $energy
);

echo remove_json_comma($data);

$MySQLi->close();
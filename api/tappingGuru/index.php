<?php

include '../../bot/config.php';
include '../../bot/functions.php';

$MySQLi = new mysqli('localhost', $DB['username'], $DB['password'], $DB['dbname']);
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

$currentTime = microtime(true) * 1000;
$currentDate = new DateTime();
$currentDate->setTimestamp($currentTime / 1000);
$currentDate->setTime(0, 0, 0);
$resetTime = $currentDate->getTimestamp() * 1000;

$tappingGuruLeft = $get_user['tappingGuruLeft'];
$tappingGuruNextTime = $get_user['tappingGuruNextTime'];

if ($currentTime >= $tappingGuruNextTime) {
    $tappingGuruLeft = 3;
    $tappingGuruNextTime = $resetTime + (24 * 60 * 60 * 1000);
} else {
    if ($tappingGuruLeft > 0) {
        $tappingGuruLeft--;
    } else {
        http_response_code(300);
        echo json_encode(['ok' => false, 'message' => 'no tapping guru attempts left'], JSON_PRETTY_PRINT);
        $MySQLi->close();
        die;
    }
}

$tappingGuruStarted = $currentTime;
$MySQLi->query("UPDATE `users` SET `tappingGuruStarted` = '{$tappingGuruStarted}' ,`tappingGuruLeft` = '{$tappingGuruLeft}', `tappingGuruNextTime` = '{$tappingGuruNextTime}' WHERE `hash` = '{$app_hash}' LIMIT 1");

$MySQLi->close();
echo json_encode(['ok' => true, 'message' => 'tapping guru updated', 'tappingGuruLeft' => $tappingGuruLeft, 'tappingGuruNextTime' => $tappingGuruNextTime], JSON_PRETTY_PRINT);
?>

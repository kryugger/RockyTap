<?php
include '../../../bot/config.php';
include '../../../bot/functions.php';

$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) die;
function ToDie($MySQLi){
$MySQLi->close();
die;
}


$user_id = $_REQUEST['q'];
$action = $_REQUEST['action'];



if($action == 'banUser'){
    $MySQLi->query("UPDATE `users` SET `step` = 'banned' WHERE `id` = '{$user_id}' LIMIT 1");
    echo json_encode(['success' => true]);
}



if($action == 'unbanUser'){
    $MySQLi->query("UPDATE `users` SET `step` = '' WHERE `id` = '{$user_id}' LIMIT 1");
    echo json_encode(['success' => true]);
}



if($action == 'changeUserScore'){
    $newScore = $_REQUEST['newScore'];
    $MySQLi->query("UPDATE `users` SET `score` = '{$newScore}' WHERE `id` = '{$user_id}' LIMIT 1");
    echo json_encode(['success' => true]);
}



if($action == 'changeUserBalance'){
    $newBalance = $_REQUEST['newBalance'];
    $MySQLi->query("UPDATE `users` SET `balance` = '{$newBalance}' WHERE `id` = '{$user_id}' LIMIT 1");
    echo json_encode(['success' => true]);
}



if($action == 'resetUserTappingGuru'){
    $MySQLi->query("UPDATE `users` SET `tappingGuruLeft` = '3' WHERE `id` = '{$user_id}' LIMIT 1");
    echo json_encode(['success' => true]);
}



if($action == 'resetUserFullTank'){
    $MySQLi->query("UPDATE `users` SET `fullTankLeft` = '3' WHERE `id` = '{$user_id}' LIMIT 1");
    echo json_encode(['success' => true]);
}



if($action == 'changeMultiTapLevel'){
    $newLevel = $_REQUEST['newLevel'];
    $MySQLi->query("UPDATE `users` SET `multitap` = '{$newLevel}' WHERE `id` = '{$user_id}' LIMIT 1");
    echo json_encode(['success' => true]);
}



if($action == 'changeEnergyLimitLevel'){
    $newLevel = $_REQUEST['newLevel'];
    $MySQLi->query("UPDATE `users` SET `energyLimit` = '{$newLevel}' WHERE `id` = '{$user_id}' LIMIT 1");
    echo json_encode(['success' => true]);
}



if($action == 'changeRechargingSpeedLevel'){
    $newLevel = $_REQUEST['newLevel'];
    $MySQLi->query("UPDATE `users` SET `rechargingSpeed` = '{$newLevel}' WHERE `id` = '{$user_id}' LIMIT 1");
    echo json_encode(['success' => true]);
}



if($action == 'sendMessageToUser'){
    $text = $_REQUEST['text'];
    LampStack('sendMessage',[
        'chat_id' => $user_id,
        'text' => $text,
        'parse_mode' => 'HTML',
    ]);
    echo json_encode(['success' => true]);
}








$MySQLi->close();
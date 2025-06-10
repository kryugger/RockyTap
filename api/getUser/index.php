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


if($get_user['step'] == 'banned'){
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'user not found'], JSON_PRETTY_PRINT);
    $MySQLi->close();
    die;
}


//          calculate user energy           //
$remaining_time = time() - $get_user['lastTapTime'];
$calulated_energy = ($remaining_time * $get_user['rechargingSpeed']) + $get_user['energy'];
if($calulated_energy > $get_user['energyLimit'] * 500) $calulated_energy = $get_user['energyLimit'] * 500;
$MySQLi->query("UPDATE `users` SET `energy` = '{$calulated_energy}' WHERE `hash` = '{$app_hash}' LIMIT 1");


//          calculate user tappingGuruLeft           //
$tappingGuruLeft = $get_user['tappingGuruLeft'];
if(microtime(true) * 1000 >= $get_user['tappingGuruNextTime']){
    if($tappingGuruLeft >= 3){
        $tappingGuruLeft = 3;
    }else{
        $tappingGuruLeft++;
    }
    $tappingGuruNextTime = microtime(true) * 1000 + (6 * 60 * 60 * 1000);
    $MySQLi->query("UPDATE `users` SET `tappingGuruNextTime` = '{$tappingGuruNextTime}', `tappingGuruLeft` = '{$tappingGuruLeft}' WHERE `hash` = '{$app_hash}' LIMIT 1");
}


//          calculate user fullTankLeft           //
$fullTankLeft = $get_user['fullTankLeft'];
if(microtime(true) * 1000 >= $get_user['fullTankNextTime']){
    if($fullTankLeft >= 3){
        $fullTankLeft = 3;
    }else{
        $fullTankLeft++;
    }
    $fullTankNextTime = microtime(true) * 1000 + (6 * 60 * 60 * 1000);
    $MySQLi->query("UPDATE `users` SET `fullTankNextTime` = '{$fullTankNextTime}', `fullTankLeft` = '{$fullTankLeft}' WHERE `hash` = '{$app_hash}' LIMIT 1");
}



$get_user = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `users` WHERE `hash` = '{$app_hash}' LIMIT 1"));



$user_details['score'] = $get_user['score'];
$user_details['balance'] = $get_user['balance'];
$user_details['energy'] = $get_user['energy'];
$user_details['multitap'] = $get_user['multitap'];
$user_details['energyLimit'] = $get_user['energyLimit'];
$user_details['rechargingSpeed'] = $get_user['rechargingSpeed'];
$user_details['referrals'] = $get_user['referrals'];
$user_details['tappingGuruLeft'] = $get_user['tappingGuruLeft'];



if($get_user['tappingGuruLeft'] == 0){
$user_details['tappingGuruNextTime'] = $get_user['tappingGuruNextTime'];
}



$user_details['fullTankLeft'] = $get_user['fullTankLeft'];
$user_details['fullTankNextTime'] = $get_user['fullTankNextTime'];



//          calculate user and bot missions           //

$user_details['missions'] = [];

$get_missions = mysqli_fetch_all(mysqli_query($MySQLi, "SELECT missions.id AS mission_id, missions.reward, missions.name AS mission_name, missions.description, tasks.id AS task_id, tasks.name AS task_name, tasks.chatId, tasks.url, tasks.type FROM missions LEFT JOIN tasks ON missions.id = tasks.mission_id ORDER BY missions.id, tasks.id"), MYSQLI_ASSOC);

if($get_missions){
$mission_index = -1;
$current_mission_id = null;

foreach($get_missions as $row){

// Check if we are starting a new mission
if ($row['mission_id'] !== $current_mission_id) {
$current_mission_id = $row['mission_id'];
$mission_index++;

$status = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `user_missions` WHERE `user_id` = '{$get_user['id']}' and `mission_id` = '{$current_mission_id}' LIMIT 1"))['status']?:0;

$user_details['missions'][$mission_index]['status'] = (int) $status;
$user_details['missions'][$mission_index]['id'] = $row['mission_id'];
$user_details['missions'][$mission_index]['reward'] = (int) $row['reward'];
$user_details['missions'][$mission_index]['name'] = $row['mission_name'];
$user_details['missions'][$mission_index]['description'] = $row['description'];
$user_details['missions'][$mission_index]['tasks'] = array();
}


$task_details = array(
'id' => $row['task_id'],
'name' => $row['task_name'],
'type' => intval($row['type']),
'status' => (int) mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `user_tasks` WHERE `user_id` = '{$get_user['id']}' AND `task_id` = '{$row['task_id']}' LIMIT 1"))['status']?:0
);

// Only add URL if type is 0 (URL type)
if ($row['type'] == 0) {
$task_details['url'] = $row['url'];
}else{
$task_details['chatId'] = $row['chatId'];
}
$user_details['missions'][$mission_index]['tasks'][] = $task_details;
}
}



//          calculate refTasks          //
$get_refTasks = array_column(mysqli_fetch_all(mysqli_query($MySQLi, "SELECT `refLevel` FROM `refTasks` WHERE `user_id` = '{$get_user['id']}'"), MYSQLI_ASSOC), 'refLevel');
$refTasks = array_values(array_diff(array(1, 3, 10, 25, 50, 100, 500, 1000, 10000, 100000), $get_refTasks));
$user_details['refTasks'] = $refTasks;



//          calculate leaguesTasks          //
$get_leaguesTasks = array_column(mysqli_fetch_all(mysqli_query($MySQLi, "SELECT `league` FROM `leaguesTasks` WHERE `user_id` = '{$get_user['id']}'"), MYSQLI_ASSOC), 'league');
$leaguesTasks = array_values(array_diff(array("bronze", "silver", "gold", "platinum", "diamond", "master", "grandmaster", "elite", "legendary"), $get_leaguesTasks));
$user_details['leaguesTasks'] = $leaguesTasks;



$user_details['totalReferralsRewards'] = $get_user['totalReferralsRewards'];

$user_details['timestamp'] = round(microtime(true) * 1000);




echo remove_json_comma($user_details);

$MySQLi->close();
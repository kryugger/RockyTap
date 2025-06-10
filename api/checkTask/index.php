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


$taskId = json_decode(file_get_contents('php://input'), true)['taskId'];


$get_task_time = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `user_tasks` WHERE `user_id` = '{$get_user['id']}' AND `task_id` = '{$taskId}' LIMIT 1"))['check_time'];


// if(time() - $get_task_time < 20){
//     echo '{"status": "wait"}';
//     $MySQLi->close();
//     die;
// }

$get_task = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `tasks` WHERE `id` = '{$taskId}' LIMIT 1"));

if(!$get_task){
    echo '{"status": "wait"}';
    $MySQLi->close();
    die;
}

if($get_task['type'] == 1){
    $result = json_decode(file_get_contents('https://api.telegram.org/bot'.$apiKey.'/getChatMember?chat_id='.$get_task['url'].'&user_id='.$get_user['id']));
    
    if($result->ok and in_array($result->result->status, ['member', 'administrator'])){
        $MySQLi->query("UPDATE `user_tasks` SET `status` = '3' WHERE `task_id` = '{$taskId}' LIMIT 1");
        echo '{"status": "ok"}';
    }else{
        echo '{"status": "wait"}';
    }

}else{
    $MySQLi->query("UPDATE `user_tasks` SET `status` = '3' WHERE `task_id` = '{$taskId}' LIMIT 1");
    echo '{"status": "ok"}';
}





$MySQLi->close();

// echo '{"status": "wait"}';
// echo '{"status": "ok"}';
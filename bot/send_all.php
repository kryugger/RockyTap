<?php
include ('./config.php');
include ('./functions.php');
ini_set('max_execution_time', 30);

$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) die;


$getDB = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `sending` LIMIT 1"));
if(!$getDB){
    $MySQLi->close;
    die;
}

$getUsers = mysqli_fetch_all(mysqli_query($MySQLi,"SELECT `id` FROM `users` LIMIT 100 OFFSET {$getDB['count']}"));
$plus = $getDB['count'] + 100;
$MySQLi->query("UPDATE `sending` SET `count` = {$plus} LIMIT 1");


if($getDB['type'] == 'send'){
foreach($getUsers as $id){
LampStack('copyMessage',[
'chat_id' => $id[0],
'from_chat_id' => $getDB['chat_id'],
'message_id' => $getDB['msg_id']
]);
usleep(200000);
}
}
if($getDB['type'] == 'forward'){
foreach($getUsers as $id){
LampStack('ForwardMessage',[
'chat_id' => $id[0],
'from_chat_id' => $getDB['chat_id'],
'message_id' => $getDB['msg_id']
]);
usleep(200000);
}
}

$ToCheck = $MySQLi->query("SELECT `id` FROM `users`")->num_rows;
if($plus >= $ToCheck){
foreach($admins_user_id as $id){
LampStack('sendmessage',[
'chat_id'=> $id,
'text'=> 'Send|Forward operation to all users successfully completed ✅',
]);
usleep(100000);
}
$MySQLi->query("DELETE FROM `sending` WHERE `type` = 'send' OR `type` = 'forward'");
}

$MySQLi->close;
die;
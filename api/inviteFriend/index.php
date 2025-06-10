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


$string = 'https://t.me/share/url?url=';
$string .= 'https://t.me/' . $botUsername . '?start=' . $get_user['id'];
$string .= '&text=';
$string .= str_replace('+', ' ', urlencode('🎁 +2.5k Coins as a first-time gift'));

LampStack('sendMessage',[
'chat_id' => $get_user['id'],
'text' => 'Invite friend and get <b>2500</b> coins!
If they have <b>Telegram premium</b> you will get <b>10,000</b> coins',
'parse_mode' => 'HTML',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=> 'Send Invite To Friends', 'url' => $string]],
]
])
]);


$MySQLi->close();
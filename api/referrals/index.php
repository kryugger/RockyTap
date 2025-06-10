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

$get_referrals = mysqli_fetch_all(mysqli_query($MySQLi, "SELECT `id`, `first_name`, `score`, `is_premium` FROM `users` WHERE `inviter_id` = '{$get_user['id']}' LIMIT 1000"), MYSQLI_ASSOC);

if(!$get_referrals) {
    echo '[]';
}else{
$referrals = array();
$c = 0;
foreach($get_referrals as $item){
    $referrals[$c]["id"] = (int) $item['id'];
    $referrals[$c]["name"] = $item['first_name'];
    $referrals[$c]["scores"] = (int) $item['score'];
    if($item['is_premium']) $referrals[$c]["rewards"] = 10000;
    else $referrals[$c]["rewards"] = 2500;
    $c++;
}
echo json_encode($referrals);
}

$MySQLi->close();
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


@$totalPlayers = $MySQLi->query("SELECT `id` FROM `users`")->num_rows?:0;


$start_timestamp = time() - (1 * 24 * 60 * 60);
$end_timestamp = time();
@$daily = $MySQLi->query("SELECT `id` FROM `users` WHERE `joining_date` BETWEEN $start_timestamp AND $end_timestamp")->num_rows?:0;


$start_timestamp = time() - (2 * 60 * 60);
$end_timestamp = time();
@$online = $MySQLi->query("SELECT `id` FROM `users` WHERE `lastTapTime` BETWEEN $start_timestamp AND $end_timestamp")->num_rows?:0;



@$totalCoins = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT SUM(`balance`) AS sum FROM `users`"))['sum']?:0;
@$totalTaps = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT SUM(`score`) AS sum FROM `users`"))['sum']?:0;




$data = array(
	/*
    "totalCoins" => $totalCoins,
    "totalTaps" => $totalTaps,
   // "totalPlayers" => $totalPlayers,
	"totalPlayers" => 80000,
    "daily" => $daily,
    "online" => $online,
	
	*/
	
	 "totalCoins" => rand(4380000, 23000000),
	   "totalTaps" => $totalTaps = rand(5380000, 83000000),
"totalPlayers" => ,
"daily" => $daily = rand(15000, 35000),
 "online" => $online = rand(10000, 15000),
);

echo remove_json_comma($data);


$MySQLi->close();
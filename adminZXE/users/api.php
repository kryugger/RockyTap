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

$q = $_REQUEST['q'];

$get_all = mysqli_fetch_all(mysqli_query($MySQLi, "SELECT * FROM `users` WHERE `id` = '{$q}' LIMIT 30"), MYSQLI_ASSOC);
if(!$get_all) $get_all = mysqli_fetch_all(mysqli_query($MySQLi, "SELECT * FROM `users` WHERE `first_name` LIKE '%{$q}%' OR `last_name` LIKE '%{$q}%' OR `username` LIKE '%{$q}%' LIMIT 30"), MYSQLI_ASSOC);
$MySQLi->close();
echo json_encode($get_all);
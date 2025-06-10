<?php

include ('../bot/config.php');

$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error){
echo 'Connection failed: ' . $MySQLi->connect_error;
$MySQLi->close();
die;
}


//          users            //
$query = "CREATE TABLE users (
id BIGINT(255) PRIMARY KEY,
step VARCHAR(255) DEFAULT NULL,
first_name VARCHAR(255) DEFAULT NULL,
last_name VARCHAR(255) DEFAULT NULL,
username VARCHAR(255) DEFAULT NULL,
is_premium INT(1) DEFAULT 0,
language_code VARCHAR(64) DEFAULT 'en',
hash VARCHAR(255) DEFAULT NULL,
tdata VARCHAR(1028) DEFAULT NULL,
score BIGINT DEFAULT 0,
balance BIGINT DEFAULT 0,
energy BIGINT DEFAULT 500,
multitap INT DEFAULT 1,
energyLimit INT DEFAULT 1,
rechargingSpeed INT DEFAULT 1,
referrals INT DEFAULT 0,
inviter_id BIGINT(255) DEFAULT NULL,
tappingGuruLeft INT DEFAULT 3,
tappingGuruStarted BIGINT DEFAULT NULL,
tappingGuruNextTime BIGINT DEFAULT NULL,
fullTankLeft INT DEFAULT 3,
fullTankNextTime BIGINT DEFAULT 43200000,
lastTapTime BIGINT DEFAULT NULL,
totalReferralsRewards BIGINT DEFAULT 0,
joining_date BIGINT DEFAULT NULL
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//          missions            //
$query = "CREATE TABLE missions (
id INT PRIMARY KEY AUTO_INCREMENT,
reward INT,
name VARCHAR(128),
description TEXT
) DEFAULT CHARSET = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//          user_missions            //
$query = "CREATE TABLE user_missions (
id INT PRIMARY KEY AUTO_INCREMENT,
user_id BIGINT(255),
mission_id INT,
status INT,
FOREIGN KEY (user_id) REFERENCES users(id),
FOREIGN KEY (mission_id) REFERENCES missions(id)
) DEFAULT CHARSET = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//          tasks            //
$query = "CREATE TABLE tasks (
id INT PRIMARY KEY AUTO_INCREMENT,
mission_id INT,
name VARCHAR(255),
chatId VARCHAR(255),
url VARCHAR(255),
type INT,
FOREIGN KEY (mission_id) REFERENCES missions(id)
) DEFAULT CHARSET = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//          user_tasks            //
$query = "CREATE TABLE user_tasks (
id INT PRIMARY KEY AUTO_INCREMENT,
user_id BIGINT(255),
task_id INT,
status INT,
check_time BIGINT,
FOREIGN KEY (user_id) REFERENCES users(id),
FOREIGN KEY (task_id) REFERENCES tasks(id)
) DEFAULT CHARSET = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//          refTasks            //
$query = "CREATE TABLE refTasks (
id INT PRIMARY KEY AUTO_INCREMENT,
user_id BIGINT(255),
refLevel INT,
FOREIGN KEY (user_id) REFERENCES users(id)
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//          leaguesTasks            //
$query = "CREATE TABLE leaguesTasks (
id INT PRIMARY KEY AUTO_INCREMENT,
user_id BIGINT(255),
league VARCHAR(50),
FOREIGN KEY (user_id) REFERENCES users(id)
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';


//          sending            //
$query = "CREATE TABLE `sending` (
`type` VARCHAR(255) PRIMARY KEY,
`chat_id` BIGINT(255) DEFAULT NULL,
`msg_id` BIGINT(255) DEFAULT NULL,
`count` BIGINT(225) DEFAULT NULL
) default charset = utf8mb4";
if($MySQLi->query($query) === false)
echo $MySQLi->error.'<br>';




echo 'done';
$MySQLi->close();
die;
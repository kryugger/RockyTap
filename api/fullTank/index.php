<?php

// Include configuration and functions
include '../../bot/config.php';
include '../../bot/functions.php';

// Connect to MySQL database
$MySQLi = new mysqli('localhost', $DB['username'], $DB['password'], $DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) {
    die('Connection failed: ' . $MySQLi->connect_error);
}

// Start session and get application hash
session_start();
$app_hash = $_SESSION['app_hash'];

// Fetch user data based on app_hash
$get_user = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT * FROM `users` WHERE `hash` = '{$app_hash}' LIMIT 1"));

// If user not found, return error response and terminate script
if (!$get_user) {
    http_response_code(300);
    echo json_encode(['ok' => false, 'message' => 'user not found'], JSON_PRETTY_PRINT);
    $MySQLi->close();
    die;
}

// Get current values from the fetched user data
$fullTankLeft = $get_user['fullTankLeft'];
$fullTankNextTime = $get_user['fullTankNextTime'];
$energyLimit = $get_user['energyLimit'];

// Configuration
$Ar = [
    'fullTanks' => 3,
    'fullTankDelay' => 12 * 60 * 60 * 1000, // 12 hours
    'energyPerLevel' => 500
];










 

if ($current_time_ms >= $fullTankNextTime) {
    $fullTankLeft = 3;
    $fullTankNextTime = $resetTime + (24 * 60 * 60 * 1000);
} else {
    if ($fullTankLeft > 0) {
        $fullTankLeft--;
    } else {
        http_response_code(300);
        echo json_encode(['ok' => false, 'message' => 'no tapping guru attempts left'], JSON_PRETTY_PRINT);
        $MySQLi->close();
        die;
    }
}





// Calculate energy based on energyLimit
$energy = $energyLimit * $Ar['energyPerLevel'];

// Update user data in the database
$MySQLi->query("UPDATE `users` SET `fullTankLeft` = '{$fullTankLeft}', `fullTankNextTime` = '{$fullTankNextTime}', `energy` = '{$energy}' WHERE `hash` = '{$app_hash}' LIMIT 1");

// Close MySQL connection
$MySQLi->close();

// Return the current status and remaining time
echo json_encode([
    'ok' => true,
    'fullTankLeft' => $fullTankLeft,
    'remainingTime' => $remainingTime,
    'energy' => $energy
], JSON_PRETTY_PRINT);

?>

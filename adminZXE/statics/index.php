<!DOCTYPE html>
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



@$totalPlayers = $MySQLi->query("SELECT `id` FROM `users`")->num_rows?:0;


$start_timestamp = time() - (1 * 24 * 60 * 60);
$end_timestamp = time();
@$dailyPlayers = $MySQLi->query("SELECT `id` FROM `users` WHERE `joining_date` BETWEEN $start_timestamp AND $end_timestamp")->num_rows?:0;


$start_timestamp = time() - (7 * 24 * 60 * 60);
$end_timestamp = time();
@$weeklyPlayers = $MySQLi->query("SELECT `id` FROM `users` WHERE `joining_date` BETWEEN $start_timestamp AND $end_timestamp")->num_rows?:0;


$start_timestamp = time() - (30 * 24 * 60 * 60);
$end_timestamp = time();
@$monthlyPlayers = $MySQLi->query("SELECT `id` FROM `users` WHERE `joining_date` BETWEEN $start_timestamp AND $end_timestamp")->num_rows?:0;


$start_timestamp = time() - (2 * 60 * 60);
$end_timestamp = time();
@$onlinePlayers = $MySQLi->query("SELECT `id` FROM `users` WHERE `lastTapTime` BETWEEN $start_timestamp AND $end_timestamp")->num_rows?:0;


@$totalBalance = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT SUM(`balance`) AS sum FROM `users`"))['sum']?:0;


@$totalTaps = mysqli_fetch_assoc(mysqli_query($MySQLi, "SELECT SUM(`score`) AS sum FROM `users`"))['sum']?:0;


@$premiumPlayers = $MySQLi->query("SELECT `id` FROM `users` WHERE `is_premium` = 1")->num_rows?:0;


@$invitedPlayers = $MySQLi->query("SELECT `id` FROM `users` WHERE `inviter_id` IS NOT NULL")->num_rows?:0;



@$bronze_claimed = $MySQLi->query("SELECT `id` FROM `leaguesTasks` WHERE `league` = 'bronze'")->num_rows?:0;
@$silver_claimed = $MySQLi->query("SELECT `id` FROM `leaguesTasks` WHERE `league` = 'silver'")->num_rows?:0;
@$gold_claimed = $MySQLi->query("SELECT `id` FROM `leaguesTasks` WHERE `league` = 'gold'")->num_rows?:0;
@$platinum_claimed = $MySQLi->query("SELECT `id` FROM `leaguesTasks` WHERE `league` = 'platinum'")->num_rows?:0;
@$diamond_claimed = $MySQLi->query("SELECT `id` FROM `leaguesTasks` WHERE `league` = 'diamond'")->num_rows?:0;
@$master_claimed = $MySQLi->query("SELECT `id` FROM `leaguesTasks` WHERE `league` = 'master'")->num_rows?:0;
@$grandmaster_claimed = $MySQLi->query("SELECT `id` FROM `leaguesTasks` WHERE `league` = 'grandmaster'")->num_rows?:0;
@$elite_claimed = $MySQLi->query("SELECT `id` FROM `leaguesTasks` WHERE `league` = 'elite'")->num_rows?:0;
@$legendary_claimed = $MySQLi->query("SELECT `id` FROM `leaguesTasks` WHERE `league` = 'legendary'")->num_rows?:0;


$MySQLi->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'CustomFont';
            src: url('./CustomFont.woff2') format('woff2');
        }
        body {
            font-family: 'CustomFont', sans-serif;
            background-color: #FFFFFF;
            color: #000000;
        }
        .stat-item {
            border: 1px solid #000000;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-8 text-center">Application Statistics</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Total Users</h2>
                <p class="text-3xl font-bold"><?= number_format($totalPlayers); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Premium Users</h2>
                <p class="text-3xl font-bold"><?= number_format($premiumPlayers); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Invited Users</h2>
                <p class="text-3xl font-bold"><?= number_format($invitedPlayers); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Today's Users</h2>
                <p class="text-3xl font-bold"><?= number_format($dailyPlayers); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">This Week Users</h2>
                <p class="text-3xl font-bold"><?= number_format($weeklyPlayers); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">This Month Users</h2>
                <p class="text-3xl font-bold"><?= number_format($monthlyPlayers); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Online Players</h2>
                <p class="text-3xl font-bold"><?= number_format($onlinePlayers); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Total Balances</h2>
                <p class="text-3xl font-bold"><?= number_format($totalBalance); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Total Taps</h2>
                <p class="text-3xl font-bold"><?= number_format($totalTaps); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Bronze League Users</h2>
                <p class="text-3xl font-bold"><?= number_format($bronze_claimed); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Silver League Users</h2>
                <p class="text-3xl font-bold"><?= number_format($silver_claimed); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Gold League Users</h2>
                <p class="text-3xl font-bold"><?= number_format($gold_claimed); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Platinum League Users</h2>
                <p class="text-3xl font-bold"><?= number_format($platinum_claimed); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Diamond League Users</h2>
                <p class="text-3xl font-bold"><?= number_format($diamond_claimed); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Master League Users</h2>
                <p class="text-3xl font-bold"><?= number_format($master_claimed); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">GrandMaster League Users</h2>
                <p class="text-3xl font-bold"><?= number_format($grandmaster_claimed); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Elite League Users</h2>
                <p class="text-3xl font-bold"><?= number_format($elite_claimed); ?></p>
            </div>
            <div class="stat-item">
                <h2 class="text-lg font-semibold mb-2">Legendary League Users</h2>
                <p class="text-3xl font-bold"><?= number_format($legendary_claimed); ?></p>
            </div>
        </div>
    </div>
</body>
</html>

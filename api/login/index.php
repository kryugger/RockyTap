<?php

include '../../bot/config.php';

$MySQLi = new mysqli('localhost',$DB['username'],$DB['password'],$DB['dbname']);
$MySQLi->query("SET NAMES 'utf8'");
$MySQLi->set_charset('utf8mb4');
if ($MySQLi->connect_error) die;
function ToDie($MySQLi){
    $MySQLi->close();
    die;
}

function validate_telegram_hash($telegram_data, $bot_token, $received_hash) {
    $data = [
        'auth_date' => $telegram_data['auth_date'],
        'query_id' => $telegram_data['query_id'],
        'user' => $telegram_data['user'],
    ];
    $data_check_string = '';
    ksort($data);
    foreach ($data as $key => $value) {
        $data_check_string .= "$key=$value\n";
    }
    $data_check_string = rtrim($data_check_string, "\n");
    $secret_key = hash_hmac('sha256', $bot_token, 'WebAppData', true);
    $computed_hash = hash_hmac('sha256', $data_check_string, $secret_key);
    return $computed_hash == $received_hash;
}

$headers = getallheaders();

if(isset($headers['Telegram-Data'])) $headers = $headers['Telegram-Data'];
else $headers = $headers['telegram-data'];

parse_str($headers, $telegram_data);
$user_id = json_decode($telegram_data['user'], true)['id'];
$hash = $telegram_data['hash'];

file_put_contents('tdata.txt', urlencode($headers));

if (!validate_telegram_hash($telegram_data, $apiKey, $hash)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'invalid request'], JSON_PRETTY_PRINT);
    $MySQLi->close();
    die;
}

$tdata = urlencode($headers);
$MySQLi->query("UPDATE `users` SET `hash` = '{$hash}', `tdata` = '{$tdata}' WHERE `id` = '{$user_id}' LIMIT 1");

session_start();
$_SESSION['app_hash'] = $hash;


echo $hash;

$MySQLi->close();
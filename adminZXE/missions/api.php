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

$action = $_GET['action'];

if ($action == 'getMissions') {
    $missions = [];
    $sql = "SELECT * FROM missions";
    $result = $MySQLi->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $mission_id = $row['id'];
            $tasks = [];
            $taskSql = "SELECT * FROM tasks WHERE mission_id = $mission_id";
            $taskResult = $MySQLi->query($taskSql);

            if ($taskResult->num_rows > 0) {
                while($taskRow = $taskResult->fetch_assoc()) {
                    $tasks[] = $taskRow;
                }
            }

            $row['tasks'] = $tasks;
            $missions[] = $row;
        }
    }

    echo json_encode(['missions' => $missions]);
} elseif ($action == 'addMission') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $reward = $data['reward'];
    $description = $data['description'];

    $stmt = $MySQLi->prepare("INSERT INTO missions (name, reward, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $reward, $description);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
} elseif ($action == 'removeMission') {
    $id = $_GET['id'];
    $MySQLi->query("DELETE FROM `tasks` WHERE `mission_id` = '{$id}'");
    $MySQLi->query("DELETE FROM `user_missions` WHERE `mission_id` = '{$id}'");

    $stmt = $MySQLi->prepare("DELETE FROM missions WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
} elseif ($action == 'addTask') {
    $data = json_decode(file_get_contents('php://input'), true);
    $missionId = $data['missionId'];
    $name = $data['name'];
    $chatId = $data['chatId'];
    $url = $data['url'];
    $type = $data['type'];

    $stmt = $MySQLi->prepare("INSERT INTO tasks (mission_id, name, chatId, url, type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $missionId, $name, $chatId, $url, $type);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
} elseif ($action == 'removeTask') {
    $id = $_GET['id'];
    $MySQLi->query("DELETE FROM `user_tasks` WHERE `task_id` = '{$id}'");
    $stmt = $MySQLi->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}

$MySQLi->close();
?>
<?php

include_once 'DatabaseController.php';
require_once 'lib.php';

$db = new DatabaseController();

if (!isset($_POST['token']))
    die(json_encode('token not set'));

$token = $_POST['token'];

$tokenDecoded = getTokenDecoded($token);

if (!$tokenDecoded)
    die(json_encode('token not valid'));

$userId = $tokenDecoded['userId'];

$data = $db->getRows('SELECT * FROM `friends` WHERE `user_id` = ? ORDER BY `created_at_timestamp` DESC', [$userId]);

$friends = array_map(function ($friend) {
    return [
        'id' => intval($friend['id']),
        'name' => $friend['name'],
        'bio' => $friend['bio']
    ];
}, $data);

exit(json_encode(['status' => 'success', 'friends' => $friends]));

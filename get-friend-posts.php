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

$data = $db->getRows('SELECT * FROM `friend_posts` WHERE `user_id` = ? ORDER BY `created_at_timestamp` DESC', [$userId]);

$posts = array_map(function ($post) use ($db) {
    $friendId = intval($post['friend_id']);
    $datum = $db->getRow('SELECT `name` FROM `friends` WHERE `id` = ?', [$friendId]);
    $friendName = $datum['name'];
    return [
        'id' => intval($post['id']),
        'friendName' => $friendName,
        'title' => $post['title'],
        'message' => $post['message'],
        'createdAtTimestamp' => intval($post['created_at_timestamp'])
    ];
}, $data);

exit(json_encode(['status' => 'success', 'posts' => $posts]));

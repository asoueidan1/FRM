<?php

include_once 'DatabaseController.php';
require_once 'lib.php';

$db = new DatabaseController();

if (!isset($_POST['token']) || !isset($_POST['friendId']) || !isset($_POST['title']) || !isset($_POST['message']))
    die(json_encode('token or friendId or title or message not set'));

$token = $_POST['token'];

$tokenDecoded = getTokenDecoded($token);

if (!$tokenDecoded)
    die(json_encode('token not valid'));

$userId = $tokenDecoded['userId'];

$friendId = $_POST['friendId'];
$title = trim($_POST['title']);
$message = nl2br(trim($_POST['message']));

$data = $db->getRow('SELECT 1 FROM `friends` WHERE `id` = ? AND `user_id` = ?', [$friendId, $userId]);
if (!$data)
    die(json_encode('friendId not valid'));

$data = $db->getRow('SELECT 1 FROM `friend_posts` WHERE `title` = ? AND `message` = ? AND `friend_id` = ? AND `user_id` = ?', [
    $title,
    $message,
    $friendId,
    $userId
]);

if ($data)
    $errors[] = 'Duplicate friend post!';

if (!empty($errors))
    die(json_encode(['status' => 'error', 'errors' => implode('; ', $errors)]));

$db->insertRow('INSERT INTO `friend_posts` (`user_id`, `title`, `message`, `friend_id`, `created_at_timestamp`) VALUES (?, ?, ?, ?, ?)', [
    $userId,
    $title,
    $message,
    $friendId,
    $db->serverTimestamp
]);

exit(json_encode(['status' => 'success']));

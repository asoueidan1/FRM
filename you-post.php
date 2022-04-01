<?php

include_once 'DatabaseController.php';
require_once 'lib.php';

$db = new DatabaseController();

if (!isset($_POST['title']) || !isset($_POST['message']) || !isset($_POST['token']))
    die(json_encode('title or email or token not set'));

$title = trim($_POST['title']);
$message = nl2br(trim($_POST['message']));
$token = $_POST['token'];

$tokenDecoded = getTokenDecoded($token);

if (!$tokenDecoded)
    die(json_encode('token not valid'));

$userId = $tokenDecoded['userId'];

$errors = [];

if (empty($title))
    $errors[] = 'Title is required.';
if (empty($message))
    $errors[] = 'Message is required.';

if (!empty($errors))
    die(json_encode(['status' => 'error', 'errors' => implode('; ', $errors)]));

$data = $db->getRow('SELECT 1 FROM `you_posts` WHERE `title` = ? AND `message` = ? AND `user_id` = ?', [
    $title,
    $message,
    $userId
]);

if ($data)
    $errors[] = 'Duplicate posts!';

if (!empty($errors))
    die(json_encode(['status' => 'error', 'errors' => implode('; ', $errors)]));

$db->insertRow('INSERT INTO `you_posts` (`user_id`, `title`, `message`, `created_at_timestamp`) VALUES (?, ?, ?, ?)', [
    $userId,
    $title,
    $message,
    $db->serverTimestamp
]);

exit(json_encode(['status' => 'success']));
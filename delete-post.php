<?php

include_once 'DatabaseController.php';
require_once 'lib.php';

$db = new DatabaseController();

if (!isset($_POST['token']) || !isset($_POST['postId']))
    die(json_encode('token or postId not set'));

$token = $_POST['token'];
$postId = $_POST['postId'];

$tokenDecoded = getTokenDecoded($token);

if (!$tokenDecoded)
    die(json_encode('token not valid'));

$userId = $tokenDecoded['userId'];

$db->deleteRow('DELETE FROM `you_posts` WHERE `id` = ? AND `user_id` = ?', [
    $postId,
    $userId
]);

exit(json_encode(['status' => 'success']));
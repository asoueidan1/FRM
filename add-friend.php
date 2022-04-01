<?php

include_once 'DatabaseController.php';
require_once 'lib.php';

$db = new DatabaseController();

if (!isset($_POST['name']) || !isset($_POST['bio']) || !isset($_POST['token']))
    die(json_encode('token or bio or name not set'));

$token = $_POST['token'];
$name = trim($_POST['name']);
$bio = trim($_POST['bio']);

$errors = [];

if (empty($name))
    $errors[] = 'Name is required.';

if (!empty($errors))
    die(json_encode(['status' => 'error', 'errors' => implode('; ', $errors)]));

$tokenDecoded = getTokenDecoded($token);

if (!$tokenDecoded)
    die(json_encode('token not valid'));

$userId = $tokenDecoded['userId'];

$data = $db->getRow('SELECT 1 FROM `friends` WHERE LOWER(`name`) = ? AND `user_id` = ?', [
    strtolower($name),
    $userId
]);

$errors = [];

if ($data)
    $errors[] = 'Duplicate friend name!';

if (!empty($errors))
    die(json_encode(['status' => 'error', 'errors' => implode('; ', $errors)]));

$db->insertRow('INSERT INTO `friends` (`user_id`, `name`, `bio`, `created_at_timestamp`) VALUES (?, ?, ?, ?)', [
    $userId,
    $name,
    $bio,
    $db->serverTimestamp
]);

exit(json_encode(['status' => 'success']));
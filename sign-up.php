<?php

include_once 'DatabaseController.php';
require_once 'lib.php';

$db = new DatabaseController();

if (!isset($_POST['email']) || !isset($_POST['password']))
    die(json_encode('email or password not set'));

$email = trim(strtolower($_POST['email']));
$password = $_POST['password'];

$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = 'Email is invalid.';

if (strlen($password) < 8)
    $errors[] = 'Password must have 8 characters or more.';

if (!empty($errors))
    die(json_encode(['status' => 'error', 'errors' => implode('; ', $errors)]));

$data = $db->getRow('SELECT 1 FROM `users` WHERE `email` =  ?', [$email]);

if ($data)
    $errors[] = 'That email is taken. Try another.';

if (!empty($errors))
    die(json_encode(['status' => 'error', 'errors' => implode('; ', $errors)]));

$db->insertRow('INSERT INTO `users` (`email`, `password_hash`, `created_at_timestamp`) VALUES (?, ?, ?)', [
    $email,
    password_hash($password, PASSWORD_DEFAULT),
    $db->serverTimestamp
]);

$userId = intval($db->lastInsertId());

$token = getToken(['userId' => $userId]);

exit(json_encode(['status' => 'success', 'token' => $token]));

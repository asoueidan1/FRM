<?php

include_once 'DatabaseController.php';
require_once 'lib.php';

$db = new DatabaseController();

if (!isset($_POST['email']) || !isset($_POST['password']))
    die(json_encode('email or password not set'));

$email = trim(strtolower($_POST['email']));
$password = $_POST['password'];

$errors = [];

$data = $db->getRow('SELECT `id`, `password_hash` FROM `users` WHERE `email` = ?', [$email]);

if (!$data)
    $errors[] = "The account doesn't exist.";

if (!empty($errors))
    die(json_encode(['status' => 'error', 'errors' => implode('; ', $errors)]));

$passwordHash = $data['password_hash'];

if (!password_verify($password, $passwordHash))
    $errors[] = 'Wrong password. Try again.';

if (!empty($errors))
    die(json_encode(['status' => 'error', 'errors' => implode('; ', $errors)]));

$userId = intval($data['id']);

$token = getToken(['userId' => $userId]);

exit(json_encode(['status' => 'success', 'token' => $token]));
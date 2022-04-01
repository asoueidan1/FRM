<?php

require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getToken($payload)
{
    return JWT::encode($payload, 'oasdifhoih234oih90fsh9823h98sdfsdfw334223', 'HS256');
}

function getTokenDecoded($token)
{
    return (array) JWT::decode($token, new Key('oasdifhoih234oih90fsh9823h98sdfsdfw334223', 'HS256'));
}
<?php

use \Firebase\JWT\JWT;
function generateJWT($user_id, $user_role)
{
    $key = "abcdefghijklmonopq"; // Replace with your secret key
    $payload = array(
        "user_id" => $user_id,
        "user_role" => $user_role,
        "exp" => time() + 1800,  // Token expires in 1 hour
    );
    return JWT::encode($payload, $key);
}
function isTokenExpired($token)
{
    $key = "abcdefghijklmonopq"; // Replace with your secret key
    try {
        $decoded = JWT::decode($token, $key, array('HS256'));
        $expirationTime = $decoded->exp;
        return $expirationTime < time();
    } catch (Exception $e) {
        return true; // Handle any JWT decoding exceptions here
    }
}



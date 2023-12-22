<?php
include "./connect.php";
session_start();
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; object-src 'none'");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Referrer-Policy: no-referrer");
/* if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    sendErrorResponse(403, "HTTPS is required for secure communication.");
    header("Location: $redirect_url", true, 301);
    exit;
} */
$allowed_origins = ['https://asbeza.ebidir.net', 'https://kegeberew.com', 'http://localhost', 'https://pb365.kegeberew.com'];
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (!in_array($origin, $allowed_origins)) {
    sendErrorResponse(403, "Invalid origin.");
    exit;
}
header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
$valid_api_key_plain = 'e-bidr@asbeza@kegbrew1';
$valid_hashed_api_key = hash('sha256', $valid_api_key_plain);
$api_key_with_csrf = isset($_SERVER['HTTP_API_KEY']) ? $_SERVER['HTTP_API_KEY'] : '';
list($api_key, $received_csrf) = explode(':', $api_key_with_csrf, 2);
if (!in_array($api_key, [$valid_hashed_api_key])) {
    sendErrorResponse(401, "Invalid API key.");
    exit;
}
$max_requests_per_minute = 100;
$configurable_max_requests = 150;
$max_requests_per_minute = isset($configurable_max_requests) ? $configurable_max_requests : $max_requests_per_minute;
$throttle_key = 'throttle_' . $_SERVER['REMOTE_ADDR'];
if (!isset($_SESSION[$throttle_key])) {
    $_SESSION[$throttle_key] = 1;
} else {
    $_SESSION[$throttle_key]++;
    if ($_SESSION[$throttle_key] > $max_requests_per_minute) {
        sendErrorResponse(429, "Too many requests. Please try again later.");
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    if (strtolower($content_type) !== 'application/json') {
        sendErrorResponse(400, "Invalid Content-Type. Expected application/json.");
        exit;
    }
    $temp_cart_json = file_get_contents("php://input");
    $temp_cart = json_decode($temp_cart_json, true);
    $csrf_token_header = isset($temp_cart['csrf_token']) ? $temp_cart['csrf_token'] : '';
    $iv = isset($temp_cart['iv']) ? $temp_cart['iv'] : '';
    if (!is_string($iv) || !preg_match('/^[a-zA-Z0-9\/+]+={0,2}$/', $iv)) {
        sendErrorResponse(400, "Invalid IV format.");
        exit;
    }
    $secret_key = 'e-bidr@asbeza@kegbrew';
    $decrypted_csrf = openssl_decrypt($received_csrf, 'aes-256-cbc', $secret_key, 0, base64_decode($iv));
    if ($decrypted_csrf !== $csrf_token_header) {
        sendErrorResponse(403, "Invalid CSRF token.");
        exit;
    }
    if ($temp_cart === null && json_last_error() !== JSON_ERROR_NONE) {
        sendErrorResponse(405, "Invalid 'temp_cart' JSON format.");
        exit;
    }
    $_SESSION['temp_cart'] = $temp_cart;
    http_response_code(200);
    logApiActivity($api_key, $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_METHOD'], $origin, 200, isset($decrypted_csrf) ? 'CSRF token: ' . $decrypted_csrf : null);
    echo json_encode(["message" => "Data received successfully!"]);
} else {
    sendErrorResponse(405, "Invalid request method.");
    exit;
}
function sendErrorResponse($responseCode, $description)
{
    global $api_key, $origin;
    logApiActivity($api_key, $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_METHOD'], $origin, $responseCode, $description);
    http_response_code($responseCode);
    echo json_encode(["error" => "Error", "description" => $description]);
    exit;
}
function logApiActivity($api_key, $ip_address, $request_method, $http_origin, $response_code, $description = null)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO apilog (api_key, ip_address, request_method, http_origin, response_code, description) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        sendErrorResponse(500, "Internal Server Error.");
    }
    $stmt->bind_param("ssssis", $api_key, $ip_address, $request_method, $http_origin, $response_code, $description);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

<?php

// Define your rate limiting parameters
$maxRequests = 60; // Maximum number of requests allowed
$perSecond = 60;  // Requests per second
$ip = $_SERVER['REMOTE_ADDR']; // Get the client's IP address

// Create a unique identifier for the client
$identifier = md5($ip);

// Create a storage directory for rate limiting data
$storageDir = __DIR__ . '/rate_limit_storage/';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

// Calculate the current timestamp and the expiration time
$timestamp = time();
$expiration = $timestamp - $perSecond;

// Clean up old rate limiting data
$files = scandir($storageDir);
foreach ($files as $file) {
    $filePath = $storageDir . $file;
    if (is_file($filePath) && filemtime($filePath) < $expiration) {
        unlink($filePath);
    }
}

// Count the number of requests made by the client
$requests = 1; // Initial request
$files = scandir($storageDir);
foreach ($files as $file) {
    $filePath = $storageDir . $file;
    if (is_file($filePath)) {
        $data = file_get_contents($filePath);
        $requestData = json_decode($data, true);
        if ($requestData['ip'] === $ip) {
            $requests++;
        }
    }
}

// Check if the client has exceeded the rate limit
if ($requests > $maxRequests) {
    // You can take action here, e.g., return an error response or log the event
    http_response_code(429); // HTTP 429 Too Many Requests
    die("Too Many Requestes Try again Letter.");
}

// Record the client's request
$filename = $storageDir . $timestamp . '-' . $identifier . '.json';
$data = json_encode(['ip' => $ip, 'timestamp' => $timestamp]);
file_put_contents($filename, $data);

// Continue with your application logic

<?php
$maxRequests = 60; // Maximum number of requests allowed
$perSecond = 60;  // Requests per second
$ip = $_SERVER['REMOTE_ADDR']; // Get the client's IP address
$identifier = md5($ip);
$storageDir = __DIR__ . '/rate_limit_storage/';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

$timestamp = time();
$expiration = $timestamp - $perSecond;
$files = scandir($storageDir);
foreach ($files as $file) {
    $filePath = $storageDir . $file;
    if (is_file($filePath) && filemtime($filePath) < $expiration) {
        unlink($filePath);
    }
}
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
if ($requests > $maxRequests) {
    http_response_code(429); // HTTP 429 Too Many Requests
    die("Too Many Requestes Try again Letter.");
}
$filename = $storageDir . $timestamp . '-' . $identifier . '.json';
$data = json_encode(['ip' => $ip, 'timestamp' => $timestamp]);
file_put_contents($filename, $data);

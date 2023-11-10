<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $result = $_POST['result'];
    $api_key = $_POST['api_key'];
    $authorizationHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
    $expectedApiKey = 'e-bidr@asbeza@kegbrew';

    if ((strcasecmp($api_key, $expectedApiKey) === 0)) {
        if ($result === 'success') {
            echo 'success'; // Return 'success' as the response.
        } else {
            echo 'error';
        }
    } else {
        echo 'Invalid API key';
    }
} else {
    echo "Invalid Request";
}

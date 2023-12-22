<?php
include "../ratelimiter.php";
include "../connect.php";
include "./functions.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('user'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
$token = htmlspecialchars($_POST['token'], ENT_QUOTES, 'UTF-8');
function validateInput($userId, $orderId, $notifyUrl, $returnUrlSuccess, $returnUrlFailure)
{
    // Check if all the required fields are present.
    if (!$userId || !$orderId || !$notifyUrl || !$returnUrlSuccess || !$returnUrlFailure) {
        $_SESSION['error'] = 'Missing required fields.';
        return false;
    }
    // Check if the order ID is valid.
    if (!is_numeric($orderId)) {
        $_SESSION['error'] = 'Invalid order ID.';
        return false;
    }

    // Check if the notify URL is valid.
    if (!filter_var($notifyUrl, FILTER_VALIDATE_URL)) {
        $_SESSION['error'] = 'Invalid notify URL.';
        return false;
    }

    // Check if the return URL for success is valid.
    if (!filter_var($returnUrlSuccess, FILTER_VALIDATE_URL)) {
        $_SESSION['error'] = 'Invalid return URL for success.';
        return false;
    }

    // Check if the return URL for failure is valid.
    if (!filter_var($returnUrlFailure, FILTER_VALIDATE_URL)) {
        $_SESSION['error'] = 'Invalid return URL for failure.';
        return false;
    }

    // All the input data is valid.
    return true;
}
$api_key = 'e-bidr@asbeza@kegbrew';
function sendPaymentResult($notify_url, $order_id, $result, $description, $api_key)
{
    $data = [
        'api_key' => $api_key,
        'order_id' => $order_id,
        'result' => $result,
        'description' => $description
    ];
    $ch = curl_init($notify_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


if (!$token || $token !== $_SESSION['token']) {
    $_SESSION['error'] = "Authorization Error";
    header("Location: index.php");
    exit();
} else if (isset($_POST['checkout'])) {
    $_SESSION['price'] = $_POST['totalprice'];
    $user_id = $_POST['id'];
    $order_id = $_POST['order_id'];
    $notify_url = $_POST['notify_url'];
    $return_url_success = $_POST['return_url_success'];
    $return_url_failure = $_POST['return_url_failure'];
    $validation_result = validateInput($user_id, $order_id, $notify_url, $return_url_failure, $return_url_success);
    if (!$validation_result) {
        header("Location: loan.php");
        exit();
    }
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $limit = $row['credit_limit'] - $_POST['totalprice'];
    $stmt->close();
    $date = date('Y-m-d h:i:s');
    $cartLog = array();
    if ($row['credit_limit'] < $_POST['totalprice']) {
        $payment_result = sendPaymentResult($notify_url, $order_id, 'error', 'The user Credit Limit is Not enough to cover the total amount', $api_key);
        if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted']) {
            $_SESSION['error'] = "Data Already Submitted ";
            header("Location:  $return_url_failure");
            exit();
        }
        $_SESSION['form_submitted'] = true;
        header("Location: $return_url_failure");
        exit();
    } elseif ($row['credit_limit'] > $_POST['totalprice']) {
        $payment_result = sendPaymentResult($notify_url, $order_id, 'success', 'The user with an id of ' . $user_id . '  Has a Credit of ' . $row['credit_limit'] . ' ETB to make a purchases of ' . $_POST['totalprice'] . ' ETB', $api_key);
        var_dump($payment_result);
        if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted']) {
            $_SESSION['error'] = "Data Already Submitted ";
            header("Location:  $return_url_success");
            exit();
        }
        $_SESSION['form_submitted'] = true;
        $sql = "INSERT INTO loans (`user_id`, `price`, `credit_score`,`order_id`, `createdOn`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sddss", $user_id, $_POST['totalprice'], $_POST['credit_score'], $order_id, $date);
        $stmt->execute();
        if ($stmt) {
            $lastInsertedLoanId = $stmt->insert_id;
            foreach ($_SESSION['cart']['temp_cart']['orderItems'] as $product) {
                if (isset($product['item_name'])) {
                    $item_name = $conn->real_escape_string(isset($product['item_name']) ? $product['item_name'] : '');
                    $item_price = $conn->real_escape_string(isset($product['item_price']) ? $product['item_price'] : '');
                    $item_image = $conn->real_escape_string(isset($product['item_image']) ? $product['item_image'] : '');
                    $item_spec = $conn->real_escape_string(isset($product['item_spec']) ? $product['item_spec'] : '');
                    $item_quantity = $conn->real_escape_string(isset($product['item_quantity']) ? $product['item_quantity'] : '');
                    $sqlcart = "INSERT INTO cart (loan_id,user_id, item_name, item_price, item_image, item_spec, item_quantity)
                    VALUES (?,?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sqlcart);
                    $stmt->bind_param("dsssssi", $lastInsertedLoanId, $user_id, $item_name, $item_price, $item_image, $item_spec, $item_quantity);
                    $cartLog[] = "Item Name: $item_name, Item Price: $item_price, Item Image: $item_image, Item Spec: $item_spec, Item Quantity: $item_quantity";
                    $stmt->execute();
                    if (!$stmt) {
                        die("SQL Error: " . $conn->error);
                    }
                }
            }
            $sql = "UPDATE users SET credit_limit = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ds", $limit, $user_id);
            $stmt->execute();
            if ($stmt) {
                insertLog($conn, $user_id, "User with " . $user_id . " Has completed payment");
                $_SESSION['success'] = "Payment Completed ";
                $errorString = implode("\n", $cartLog);
                insertLog($conn, $user_id, "User with " . $user_id . " Has Bought the following items " . $errorString);
                $payment_result = sendPaymentResult($notify_url, $order_id, 'success', 'The user with an id of ' . $user_id . '  Has a Credit of ' . $row['credit_limit'] . ' ETB to make a purchases of ' . $_POST['totalprice'] . ' ETB', $api_key);
                if ($payment_result === 'success') {
                    try {

                        header("Location: $return_url_success");
                        unset($_SESSION['cart']);
                    } catch (Exception $e) {
                        $conn->rollback();
                        $_SESSION['error'] = "An error occurred during payment processing.";
                        error_log($e->getMessage());
                    }
                } else {
                    $_SESSION['error'] = "There was An error finding the success url";
                    header("Location: loan.php");
                    exit();
                }
            } else {
                insertLog($conn, $user_id, "User with " . $user_id . " couldn't complete payment");
                $_SESSION['error'] = "Couldn't Complete the payment";
                die("SQL Error: " . $conn->error);
            }
        } else {
            die("SQL Error: " . $conn->error);
        }
    }
}
if (isset($_SESSION['error'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            var errorToast = new bootstrap.Toast(document.getElementById("error-toast"));
            errorToast.show();
            document.querySelector("#error-toast .toast-body").innerHTML = "' . $_SESSION['error'] . '";
        });
      </script>';
    unset($_SESSION['error']); // Clear the error message

}

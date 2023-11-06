 
<?php
include "../common/ratelimiter.php";
include "../connect.php";
include "./functions.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('user'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
$token = htmlspecialchars($_POST['token'], ENT_QUOTES, 'UTF-8');
if (!$token || $token !== $_SESSION['token']) {
    $_SESSION['error'] = "Authorization Error";
    header("Location: index.php");
    exit;
} else if (isset($_POST['checkout'])) {
    $_SESSION['price'] = $_POST['totalprice'];
    $user_id = $_POST['id'];
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $limit = $row['credit_limit'] - $_POST['totalprice'];
    if ($limit < 0) {
        exit;
    }
    $stmt->close();
    $date = date('Y-m-d h:i:s');
    $cartLog = array();
    $sql = "INSERT INTO loans (`user_id`, `price`, `credit_score`, `createdOn`) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdds", $user_id, $_POST['totalprice'], $_POST['credit_score'], $date);
    $stmt->execute();
    if ($stmt) {
        $lastInsertedLoanId = $stmt->insert_id;
        foreach ($_SESSION['cart'] as $product) {
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
        $sql = "UPDATE users SET credit_limit = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ds", $limit, $user_id);
        $stmt->execute();
        if ($stmt) {
            $_SESSION['user_id'] = $lastInsertedLoanId;
            header("location:../branch/paymentdone.php");
            insertLog($conn, $user_id, "User with " . $user_id . " Has completed payment");
            $errorString = implode("\n", $cartLog);
            insertLog($conn, $user_id, "User with " . $user_id . " Has Bought the following items " . $errorString);
            unset($_SESSION['cart']);
        } else {
            insertLog($conn, $user_id, "User with " . $user_id . " couldn't complete payment");
            die("SQL Error: " . $conn->error);
        }
    } else {
        die("SQL Error: " . $conn->error);
    }
}

?>



<?php
include "./connect.php";
session_start();
$csrf_token = bin2hex(random_bytes(35));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $notify_url = "https://asbeza.ebidir.net/user/notify_url.php";
  $return_url_success = "https://asbeza.ebidir.net/buy.php";
  $return_url_failure = "https://asbeza.ebidir.net/forgotpassword.php";
  $temp_cart = [
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img2.webp",
      'item_name' => "Samsung galaxy Note 10",
      'item_spec' => "256GB, Navy Blue",
      'item_price' => 100,
      'item_quantity' => 2,
    ],
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img3.webp",
      'item_name' => "Onyx Black",
      'item_spec' => "Canon EOS M50",
      'item_price' => 100,
      'item_quantity' => 1,
    ],
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp",
      'item_name' => "Samsung galaxy Note 10",
      'item_spec' => "256GB, Navy Blue",
      'item_price' => 100,
      'item_quantity' => 2,
    ],
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp",
      'item_name' => "Samsung galaxy Note 10",
      'item_spec' => "256GB, Navy Blue",
      'item_price' => 100,
      'item_quantity' => 2,
    ],
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp",
      'item_name' => "Samsung galaxy Note 10",
      'item_spec' => "256GB, Navy Blue",
      'item_price' => 100,
      'item_quantity' => 2,
    ],
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp",
      'item_name' => "Samsung galaxy Note 10",
      'item_spec' => "256GB, Navy Blue",
      'item_price' => 100,
      'item_quantity' => 2,
    ],
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp",
      'item_name' => "Samsung galaxy Note 10",
      'item_spec' => "256GB, Navy Blue",
      'item_price' => 100,
      'item_quantity' => 2,
    ],
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp",
      'item_name' => "Samsung galaxy Note 10",
      'item_spec' => "256GB, Navy Blue",
      'item_price' => 100,
      'item_quantity' => 2,
    ],
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp",
      'item_name' => "Samsung galaxy Note 10",
      'item_spec' => "256GB, Navy Blue",
      'item_price' => 100,
      'item_quantity' => 2,
    ],
    [
      'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp",
      'item_name' => "Samsung galaxy Note 10",
      'item_spec' => "256GB, Navy Blue",
      'item_price' => 100,
      'item_quantity' => 2,
    ],
    [
      'total_price' => 1200,
      'notify_url' => $notify_url,
      'return_url_success' => $return_url_success,
      'return_url_failure' => $return_url_failure,
      'order_id' => "80000",
    ],
  ];

  $orderItems = array_map(function ($item) {
    return [
      'item_image' => $item['item_image'],
      'item_name' => $item['item_name'],
      'item_price' => $item['item_price'],
      'item_quantity' => $item['item_quantity'],
      'item_spec' => $item['item_spec'],
    ];
  }, array_slice($temp_cart, 0, -1));

  $orderInfo = [
    'total_price' => $temp_cart[count($temp_cart) - 1]['total_price'],
    'notify_url' => $temp_cart[count($temp_cart) - 1]['notify_url'],
    'return_url_success' => $temp_cart[count($temp_cart) - 1]['return_url_success'],
    'return_url_failure' => $temp_cart[count($temp_cart) - 1]['return_url_failure'],
    'order_id' => $temp_cart[count($temp_cart) - 1]['order_id'],
  ];
  $secret_key = 'e-bidr@asbeza@kegbrew';
  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
  $iv = substr($iv, 0, 16);
  $encrypted_csrf = openssl_encrypt($csrf_token, 'aes-256-cbc', $secret_key, 0, $iv);
  $api_key_plain = 'e-bidr@asbeza@kegbrew1';
  $hashed_api_key = hash('sha256', $api_key_plain);
  $api_key_with_csrf = $hashed_api_key . ':' . $encrypted_csrf;
  $temp_cart = [
    'temp_cart' => [
        'orderItems' => $orderItems,
        'orderInfo' => $orderInfo,
    ],
    'csrf_token' => $csrf_token,
    'iv' => base64_encode($iv),
];
  $data_json = json_encode($temp_cart);

?>
  <script>
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/ebidir-asbeza/getdata.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('API-Key', '<?php echo $api_key_with_csrf; ?>');
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.hasOwnProperty('error')) {
            console.error('Error from getdata.php:', response.error, response.description);
            window.location.href = 'http://localhost/ebidir-asbeza/pages-misc-under-maintenance.php';
          } else if (response.hasOwnProperty('message')) {
            console.log('Response from getdata.php:', response.message);
            window.location.href = 'http://localhost/ebidir-asbeza/index.php';
          }
        } else {
          console.error('Error from getdata.php:', xhr.status, xhr.statusText);
          window.location.href = 'http://localhost/ebidir-asbeza/pages-misc-under-maintenance.php';

        }
      }
    };
    xhr.send('<?php echo $data_json; ?>');
  </script>
<?php
  exit;
}
?>


<!DOCTYPE html>

<html lang="en" class="light-style" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Blank layout - Layouts | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

  <meta name="description" content="Unlock financial opportunities and secure your future with E-bidir Asbeza. We provide accessible credit solutions that empower you to take control of your financial journey. Discover the key to financial freedom and seize the opportunities you deserve." />


  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon-16x16.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../assets/js/config.js"></script>
</head>

<body>
  <!-- Content -->

  <h4 class="fw-bold p-4">Blank Page</h4>
  <form action="http://localhost/ebidir-asbeza/buy.php" method="POST" id="checkout-form">
    <!--    <input type="hidden" name="item_image" value="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img1.webp">
    <input type="hidden" name="item_name" value="Canon EOS M50">
    <input type="hidden" name="item_spec" value="Onyx Black">
    <input type="hidden" name="item_price" value="400">
    <input type="hidden" name="item_quantity" value="1">
    <input type="hidden" name="totalprice" value=1200>
    <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">-->
    <button type="submit" id="checkout-button">Checkout using E-Bidir</button>
  </form>
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="../assets/vendor/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
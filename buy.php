<?php
include "./connect.php";
session_start();
$_SESSION['token'] = bin2hex(random_bytes(35));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Add form data to temp cart session
  $_SESSION['temp_cart'][] = [
    'item_image' => $_POST['item_image'],
    'item_name' => $_POST['item_name'],
    'item_spec' => $_POST['item_spec'],
    'item_price' => $_POST['item_price'],
    'item_quantity' => $_POST['item_quantity'],
    /* 'total_price' => $_POST['totalprice'] */
  ];
  $_SESSION['temp_cart'][] = [
    'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img2.webp",
    'item_name' => "Samsung galaxy Note 10",
    'item_spec' => "256GB, Navy Blue",
    'item_price' => 400,
    'item_quantity' => 2,
    /*  'total_price' => "1800" */
  ];
  $_SESSION['temp_cart'][] = [
    'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img3.webp",
    'item_name' => "Onyx Black",
    'item_spec' => "Canon EOS M50",
    'item_price' => 500,
    'item_quantity' => 1,
    /* 'total_price' => "1800" */
  ];
  $_SESSION['temp_cart'][] = [
    'item_image' => "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp",
    'item_name' => "Samsung galaxy Note 10",
    'item_spec' => "256GB, Navy Blue",
    'item_price' => 400,
    'item_quantity' => 2,
    /* 'total_price' => "1800" */
  ];
  header('Location: index.php');
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
  <form action="" method="POST" id="checkout-form">
    <!-- Include your cart data here as hidden inputs -->
    <input type="hidden" name="item_image" value="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img1.webp">
    <input type="hidden" name="item_name" value="Canon EOS M50">
    <input type="hidden" name="item_spec" value="Onyx Black">
    <input type="hidden" name="item_price" value="400">
    <input type="hidden" name="item_quantity" value="1">
    <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">
    <button type="submit" id="checkout-button">Checkout using E-Bidir</button>
  </form>
  <div class="buy-now">
    <a href="https://ThemeSelection.com/products/sneat-bootstrap-html-admin-template/" target="_blank" class="btn btn-danger btn-buy-now">Upgrade to Pro</a>
  </div>
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
<?php
session_start();
$_SESSION['token'] = bin2hex(random_bytes(35));
if (isset($_SESSION['role'])) {
  $loc=$_SESSION['role'];
  if($_SESSION['role'] == "EA")
  $loc ="Admin";
if($_SESSION['role'] == "delivery")
  $loc ="branch";
  header("Location:" . $_SESSION['role'] . "/index.php");
}
include "./sessioncheck.php";

?>

<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Login Basic - Pages | ThemeSelection - Bootstrap 5 HTML Admin Template - Pro</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="16x16"  href="../assets/img/favicon/favicon-16x16.png">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="./assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="./assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="./assets/css/demo.css" />
  <link rel="stylesheet" href="./main.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css" />
  <!-- Helpers -->
  <script src="./assets/vendor/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="./assets/js/config.js"></script>
</head>

<body>
  <!-- Content -->
  <div class="container-xxl">
    <!-- Toast with Placements -->
    <div id="toast-container" class="" style="z-index: 11">
      <!-- Toast notifications will be added here -->
    </div>
    <!-- Toast with Placements -->
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <svg width="100.000000pt" height="100.000000pt" viewBox="0 0 213 316" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg">
                    <path fillRule="evenodd" clipRule="evenodd" d="M74.0566 43.497L49.4793 56.6282C34.0104 85.7695 22.3393 118.033 19.9095 145.264H31.1515C31.1515 130.306 43.277 118.181 58.2346 118.181C73.1921 118.181 85.3176 130.306 85.3176 145.264H190.46C190.223 148.292 190.046 155.438 189.929 160.186L189.928 160.209C189.897 161.449 189.871 162.525 189.848 163.319H0.0593382C0.231512 157.272 0.659133 151.253 1.33831 145.264C5.36395 109.765 18.2273 75.3335 39.1178 42.5265C41.6431 38.5606 44.2858 34.6185 47.0443 30.7011C47.704 29.7666 48.3686 28.8342 49.0368 27.9038H66.6309H82.7397C84.0858 25.6579 88.6055 18.4951 90.0261 16.2641C92.5514 12.2982 95.1941 8.35612 97.9526 4.43872C98.6123 3.50427 99.2769 2.57181 99.9451 1.6414H117.539H212.405C188.872 34.6995 158.327 100.946 158.327 132.889H137.022C138.477 104.569 159.099 57.9986 175.973 27.9038L163.284 17.2347H123.07L100.969 27.9038H161.497C138.482 60.2344 122.249 96.0218 119.337 130.491C119.209 131.29 119.086 132.089 118.966 132.889H97.6615C97.7906 132.089 97.9239 131.29 98.0614 130.491C102.2 106.45 110.162 82.7487 122.019 59.911C122.589 58.8147 123.167 57.7205 123.754 56.6282L111.964 43.497H74.0566ZM21.0366 195.074C24.5265 227.538 34.764 259.776 51.5758 290.527H148.223C129.624 259.484 124.959 235.781 120.288 202.713H141.505C141.505 228.154 156.215 268.503 174.678 299.555C177.798 304.803 181.113 310.003 184.622 315.148H165.306H46.9494C16.6762 270.754 0.836592 222.32 0 173.988H188.726V192.864H20.8095C20.8817 193.601 20.9574 194.338 21.0366 195.074ZM99.2692 311.865C104.255 311.865 108.297 307.824 108.297 302.838C108.297 297.852 104.255 293.81 99.2692 293.81C94.2834 293.81 90.2415 297.852 90.2415 302.838C90.2415 307.824 94.2834 311.865 99.2692 311.865Z" style="fill: #2d3a5e;" />
                  </svg>
                </span>
                <span class="app-brand-text demo text-body fw-bolder">E-bidir Asbeza</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Welcome to E-bidir! 👋</h4>
            <p class="mb-4">Please sign-in to your account</p>

            <form id="formAuthentication" class="mb-3" action="login.php" method="POST">
              <div class="mb-3">
                <label for="email" class="form-label">Phone</label>
                <input type="text" required class="form-control" id="email" name="phone" placeholder="Enter your email or username" autofocus />
              </div>
              <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Password</label>
                  <a href="forgotpassword.php">
                    <small>Forgot Password?</small>
                  </a>
                </div>
                <div class="input-group input-group-merge">
                  <input required autofocus type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" />
                  <label class="form-check-label" for="remember-me"> Remember Me </label>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                <div class="my-2 mx-auto center"><?php echo isset($_SESSION['error']) ? $_SESSION['error'] : ""; ?></div>
              </div>
            </form>

            <p class="text-center">
              <span>New on our platform?</span>
              <a href="javascript:void(0)" id="createAccountLink">
                <span>Create an account</span>
              </a>
            </p>

            <div id="modal" class="modal">
              <div class="modal-content">
                <div class="modal-header">
                  <span class="close-button" id="closeButton">&times;</span>
                </div>
                <div class="modal-body">
                  <p>Please contact the admin to create an account:</p>
                  <p>Email: <a href="mailto:amanuelgirma@gmail.com">amanuelgirma@gmail.com</a></p>
                  <p>Phone: 0923562323</p>
                </div>
                <p class="text-center">
                  <span>New on our platform?</span>
                  <a href="./support.php">
                    <span>Contact Us</span>
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="success-toast" class="bs-toast toast toast-placement-ex m-2 bg-primary top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
      <div class="toast-header">
        <strong class="me-auto">Success</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <!-- Success message will be inserted here -->
      </div>
    </div>

    <div id="error-toast" class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
      <div class="toast-header">
        <strong class="me-auto">Error</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <!-- Error message will be inserted here -->
      </div>
    </div>

    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="./assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <script src="./assets/js/common.js"></script>

    <!-- Vendors JS -->


    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/loginpagevalidation.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
      // Check if the success message exists and display the success toast
      <?php if (isset($_SESSION['success'])) : ?>
        document.addEventListener("DOMContentLoaded", function() {
          var successToast = new bootstrap.Toast(document.getElementById("success-toast"));
          successToast.show();
          document.querySelector("#success-toast .toast-body").innerHTML = "<?php echo $_SESSION['success']; ?>";
        });
        <?php unset($_SESSION['success']); // Clear the success message 
        ?>
      <?php endif; ?>

      // Check if the error message exists and display the error toast
      <?php if (isset($_SESSION['error'])) : ?>
        document.addEventListener("DOMContentLoaded", function() {
          var errorToast = new bootstrap.Toast(document.getElementById("error-toast"));
          errorToast.show();
          document.querySelector("#error-toast .toast-body").innerHTML = "<?php echo $_SESSION['error']; ?>";
        });
        <?php unset($_SESSION['error']); // Clear the error message 
        ?>
      <?php endif; ?>
    </script>
</body>

</html>
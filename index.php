<?php
session_start();
$_SESSION['token'] = bin2hex(random_bytes(35));
if (isset($_SESSION['role'])) {
  $loc = $_SESSION['role'];
  if ($_SESSION['role'] == "EA")
    $loc = "Admin";
  if ($_SESSION['role'] == "delivery")
    $loc = "branch";
  header("Location:" . $_SESSION['role'] . "/index.php");
}
include "./sessioncheck.php";
?>
<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Credit Empowerment at E-bidir Asbeza - Your Path to Financial Freedom</title>
  <meta name="description" content="Unlock financial opportunities and secure your future with E-bidir Asbeza. We provide accessible credit solutions that empower you to take control of your financial journey. Discover the key to financial freedom and seize the opportunities you deserve." />
  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon/favicon-16x16.png">
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbs5A7vbhO7frug+8pXX5h6v1/SGIdT2w2IxhLbrAsjFuCZSmKbSSUnQlmh/jpy" crossorigin="anonymous">

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
  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div id="toast-container" class="" style="z-index: 11">
        <!-- Toast notifications will be added here -->
      </div>
      <div class="card login-card">
      <div class="row no-gutters h-50">
          <div class="col-md-5">
            <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item active align-items-center">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Awash_Bank_Final_logo.jpg/2560px-Awash_Bank_Final_logo.jpg" class="w-100 " alt="Slide 1">
                </div>
                <div class="carousel-item">
                  <img src="https://media.licdn.com/dms/image/C4E03AQEUdAYuaDPGew/profile-displayphoto-shrink_400_400/0/1659367412651?e=2147483647&v=beta&t=F3NQQ9ikqhFdxLdYRqLT9xA6pqd5rk4C72Ftib_6qwk" class="w-100 " alt="Slide 2">
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>

          </div>
          <div class="col-md-6">
            <div class="card-body">
              <div class="brand-wrapper">
                <img src="assets/img/logo.svg" alt="logo" class="logo">
                <span class="app-brand-text demo text-body fw-bolder">E-bidir Asbeza</span>
              </div>
              <p class="login-card-description">Sign into your account</p>
              <form id="formAuthentication" class="mb-3" action="login.php" method="POST">
                <div class="form-group">
                  <label for="email" class="sr-only">Phone</label>
                  <input type="Tel" class="form-control" id="email" name="phone" placeholder="Enter your phone number" autofocus />

                </div>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                <div class="form-group align-items-center mb-4">
                  <label for="password" class="sr-only">Password</label>
                  <div class="input-group input-group-merge">
                    <input required autofocus type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <!--  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span> -->
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
                <button class="btn btn-block login-btn mb-4" type="submit">Sign in</button>
              </form>
              <a href="forgotpassword.php" class="forgot-password-link">
                <small>Forgot Password?</small>
              </a>
              <p class="login-card-footer-textr"><span>New on our platform?</span><a href="javascript:void(0)" class="text-reset" id="createAccountLink"><span>Create an account</span></a></p>
              <!--   <nav class="login-card-footer-nav">
                <a href="#!">Terms of use.</a>
                <a href="#!">Privacy policy</a>
              </nav> -->
              <div id="modal" class="modal">
                <div class="modal-content">
                  <div class="modal-header">
                    <span class="close-button" id="closeButton">&times;</span>
                  </div>
                  <div class="modal-body">
                    <p>Please contact the admin to create an account:</p>
                    <p>Email: <a href="mailto:amanuelgirma@gmail.com">elite.ethiopia@gmail.com</a></p>
                    <p>Phone: +251-799-20-44-17 </p>
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

          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
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
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-eMNCOIxa7wM5FKHV2TGkLE4gkTBAaBB+VH8lA6YILmd6IoAeV1GrCi3lZ4t2pXG8" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-l0DpseL39E2H9HWTZIqOznV6w/Ji6Zq5bK6ZMOnE87yKfDrMfxSk2Xp7AZoDwVPz" crossorigin="anonymous"></script>


  <!-- Vendors JS -->


  <!-- Main JS -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="./assets/js/main.js"></script>
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
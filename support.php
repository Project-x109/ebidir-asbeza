<?php
session_start();
if (isset($_SESSION['role'])) {
    header("Location:" . $_SESSION['role'] . "/index.php");
}
?>

<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login Basic - Pages | ThemeSelection - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon-16x16.png" />

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
    <link rel="stylesheet" href="./support.css" />


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
                        <form id="emailForm" action="./assets/PHPMailer/email.php" method="post" class="form">
                            <h5 class="card-title">Contact Us</h5>
                            <div class="flex">
                                <label>
                                    <input name="first_name" id="email_subject" name="email_subject" required="" placeholder="" type="text" class="input">
                                    <span>first name</span>
                                </label>

                                <label>
                                    <input name="last_name" required="" placeholder="" type="text" class="input">
                                    <span>last name</span>
                                </label>
                            </div>

                            <label>
                                <input id="recipient_email" name="recipient_email" required="" placeholder="" type="email" class="input">
                                <span>email</span>
                            </label>

                            <label>
                                <input name="contact_number" required="" type="tel" placeholder="" class="input">
                                <span>contact number</span>
                            </label>
                            <label>
                                <textarea id="email_message" name="email_message" required="" rows="3" placeholder="" class="input01"></textarea>
                                <span>message</span>
                            </label>

                            <button type="submit" class="fancy" href="#">
                                <span class="top-key"></span>
                                <span class="text">submit</span>
                                <span class="bottom-key-1"></span>
                                <span class="bottom-key-2"></span>
                            </button>
                            <div class="text-center">
                                <a href="index.php" class="d-flex align-items-center justify-content-center">
                                    <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                                    Back to login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
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

        <!-- Page JS -->

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
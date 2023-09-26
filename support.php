<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | ThemeSelection - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="./assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="./assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="./support.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
</head>


<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1  container-p-y">

                        <div class="card-body">
                            <!-- Support Center Content -->
                            <div class="row justify-content-center align-items-center">
                                <div class="col-md-4">
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


                                <!-- End Support Center Content -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content wrapper -->

                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- 
<div class="buy-now">
    <a href="https://ThemeSelection.com/products/ThemeSelection-bootstrap-html-admin-template/" target="_blank"
      class="btn btn-danger btn-buy-now">Upgrade to Pro</a>
  </div>
-->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="./assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="./assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="./assets/js/dashboards-analytics.js"></script>
    <script src="./assets/js/mark-Notification-read.js"></script>
    <script src="./assets/js/usercredithistory.js"></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
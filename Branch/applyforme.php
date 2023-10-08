<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$_SESSION['token'] = bin2hex(random_bytes(35));
// head part and all links
?>
<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | e-bidir - Bootstrap 5 HTML Admin Template - Pro</title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon/favicon.ico" />
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
    <link rel="stylesheet" href="./applyforme.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>


<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include "../common/sidebar.php"; ?> <!-- sidebar -->
            <div class="layout-page">
                <?php
                include "../common/nav.php";

                ?>
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1  container-p-y">
                        <div class="row justify-content-center align-items-center mt-5">
                            <form class="form-card" id="creditFormmain" action="backend.php" method="POST">
                                <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">

                                <p class="form-card-title">You can apply for credit here</p>
                                <p class="form-card-prompt">Insert the user's six-digit identification number</p>
                                <div class="form-card-input-wrapper">
                                    <input class="form-card-input" id="user" name="user" placeholder="______" maxlength="6" type="tel" id="identificationNumber">

                                    <div class="form-card-input-bg"></div>
                                </div>
                                <!-- <input type="text" name="data"/> -->
                                <div class="mt-5">
                                    <p class="call-again"><span class="underlined"></span></p>
                                    <button type='submit' name='dataholder' class="buttonapply">
                                        Apply
                                    </button>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="loader" id="loader">
                        <div class="loader-content">
                            <div class="spinner"></div>
                        </div>
                    </div>

                    <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" id="errorToast" style="display: none;">
                        <div class="toast-header">
                            <i class="bx bx-bell me-2"></i>
                            <div class="me-auto toast-title fw-semibold">Error</div>
                            <small></small>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                        </div>
                    </div>
                    <div class="container my-5">
                        <?php
                        include "../common/footer.php";;
                        ?>
                        <script src="../assets/js/applyform.js"></script>

</body>

</html>
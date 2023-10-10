<?php
session_start();
include "../connect.php";
include "../common/Authorization.php";
$_SESSION['token'] = bin2hex(random_bytes(35));


?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | ThemeSelection - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon-16x16.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="./userimfo.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="./userimfo.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php include "../common/sidebar.php"; ?> <!-- sidebar -->

            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php
                include "../common/nav.php";

                ?>

                <!-- / Navbar -->
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1  container-p-y">
                        <div class="row justify-content-center align-items-center mt-5">
                            <div class="container">
                                <div class="card cart">
                                    <label class="title">CHECKOUT</label>
                                    <div class="steps">
                                        <div class="step">
                                            <div class="payments">
                                                <span>User Information</span>
                                                <div class="details">
                                                    <?php
                                                    $id = $_SESSION['user_id'];
                                                    $sql = "SELECT * FROM users where user_id='$id'";
                                                    $res = $conn->query($sql);
                                                    if ($res && $res->num_rows > 0) {
                                                        $row = $res->fetch_assoc();
                                                        echo "<span>Full Name</span>
                                                       <span>{$row['name']}</span>
                                                       <span>Email:</span>
                                                       <span>{$row['email']}</span>
                                                       <span>Phone Numbre:</span>
                                                       <span>{$row['phone']}</span>
                                                       <span>TIN Number:</span>
                                                       <span>{$row['TIN_Number']}</span>
                                                       <span>Job Status:</span>
                                                       <span>Employed</span>
                                                       <span>{$_SESSION['user_id']}</span>";
                                                    } else {
                                                        echo "<span>User Data Not found</span>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div>
                                                <span>Credit Limit</span>
                                                <!-- <p>Visa</p> -->
                                                <p><?= isset($row['credit_limit']) ? $row['credit_limit'] . ' ETB' : 'User Data Not found' ?></p>
                                            </div>
                                            <hr>
                                            <div class="promo">
                                                <span>Enter Total Amount</span>
                                                <form id="checkoutForm" class="form" action="backend.php" method="POST">
                                                    <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                                                    <input class="input_field" id="total_price" name="total_price" placeholder="Enter Total Amount" required max='<?= $row['credit_limit'] ?>' type="number" step="0" onkeyup="filldata(this)">
                                                    <input type='hidden' name="user_id" id="user_id" value='<?= $id ?>' />
                                                    <button name="branch_checkout" type='submit'>Apply</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card checkout">
                                    <div class="footer">
                                        <label class="price" id="price"></label>
                                        <!-- <button class="checkout-btn">Checkout</button> -->
                                    </div>
                                </div>
                            </div>
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
                    <script>

                    </script>
                    <!-- / Content -->

                    <?php include "../common/footer.php"; ?> <!-- sidebar -->

                    <script>
                        function showLoader() {
                            $("#loader").fadeIn();
                        }

                        // Hide the loader when the response is received
                        function hideLoader() {
                            $("#loader").fadeOut();
                        }
                        // Function to check if the amount is valid
                        function filldata(e) {
                            document.getElementById('price').innerHTML = e.value + " Birr";
                        }

                        function isValidAmount(amount) {
                            return $.isNumeric(amount) && amount <= <?= $row['credit_limit'] ?>;
                        }

                        $(document).ready(function() {
                            // Add event listener to the total_price input field
                            $("#total_price").on("input", function() {
                                var total_price = $(this).val();


                                // Frontend validation for total_price
                                if (!isValidAmount(total_price)) {
                                    // Display an error message in the toast
                                    showErrorToast('Invalid amount or exceeds credit limit.');

                                } else {
                                    // Hide the error message if the amount is valid
                                    hideErrorToast();
                                }
                            });

                            // Form submission and AJAX code
                            $("#checkoutForm").on("submit", function(event) {
                                event.preventDefault();
                                var csrfToken = document.getElementById('csrf-token').getAttribute('value');
                                showLoader();

                                var user_id = $("#user_id").val();
                                var total_price = $("#total_price").val();

                                // Frontend validation for total_price
                                if (!isValidAmount(total_price)) {

                                    // Display error message in the toast
                                    showErrorToast('Invalid amount or exceeds credit limit.');
                                    return; // Don't proceed with the AJAX request
                                }


                                $.ajax({

                                    url: "backend.php",
                                    method: "POST",
                                    data: {
                                        user_id: user_id,
                                        total_price: total_price,
                                        branch_checkout: true
                                    },
                                    headers: {
                                        'X-CSRF-Token': csrfToken // Send the CSRF token as a header
                                    },
                                    dataType: "json",
                                    success: function(response) {

                                        if (response.success) {
                                            hideLoader();
                                            // Transaction successful, show success message using SweetAlert
                                            /*   Swal.fire({
                                                  icon: "success",
                                                  title: "Success",
                                                  text: response.success,
                                              }).then((result) => {
                                                  if (result.isConfirmed) { */
                                            // Redirect to the paymentdone.php page
                                            window.location.href = "paymentdone.php";
                                            /*    }
                                            }); */
                                        } else if (response.error) {
                                            hideLoader();
                                            // Display error message in the toast
                                            showErrorToast(response.error);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        hideLoader();
                                        // Display a generic error message in the toast for AJAX errors
                                        showErrorToast('An error occurred during the transaction.');
                                    }
                                });
                            });

                            // Function to display an error message in the toast
                            function showErrorToast(message) {
                                hideLoader();
                                $("#errorToast").removeClass("bg-success").addClass("bg-danger");
                                $("#errorToast .toast-title").text("Error");
                                $("#errorToast .toast-body").text(message);
                                $("#errorToast").fadeIn();
                            }

                            // Function to hide the error toast
                            function hideErrorToast() {
                                $("#errorToast").fadeOut();
                            }
                        });
                    </script>

</body>

</html>
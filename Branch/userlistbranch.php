<?php
session_start();
include "../connect.php";

?>
<!DOCTYPE html>

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
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
                  <?php include '../common/sidebar.php';?>
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

                        <div class="row">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <div class="d-flex  mt-3">
                                        <div style="margin-left: 20px;">
                                            <input type="text" class="form-control form-control-sm" id="tableSearch" placeholder="Search..." />
                                        </div>
                                    </div>
                                    <h5 class="card-header">Lists of Users</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-striped" id="table-striped">
                                            <thead>
                                                <tr>
                                                <th>No</th>
                                                    <th>Name</th>
                                                    <th>User ID</th>
                                                    <th>TIN Number</th>
                                                    <th>Date of Birth</th>
                                                    <th>Status</th>
                                                    <th>Email</th>
                                                    <th>Phone Number</th>
                                                    <th>Credit Limit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
            <?php
            $sql="SELECT *,users.status as status FROM `users`";
            $res=$conn->query($sql);
            $x=1;
            if($res->num_rows>0)
            while($row=$res->fetch_assoc()){
        $disabled=$row['status']=='paid'?"disabled":"";
        echo "<tr>
        <td>".($x++)."</td>
        <td>".$row['name']."</td>
        <td>".$row['user_id']."</td>
        <td>".$row['TIN_Number']."</td>
        <td>".$row['dob']."</td> 
        <td>".$row['status']."</td>   
        <td>".$row['email']."</td>   
        <td>".$row['phone']."</td>   
        <td>$row[credit_limit]</td>   
        </tr>";
    }
?>
        </tbody>
                                        </table>

                                        <!-- Modal Structure (empty modal) -->
                                        <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalToggleLabel">Loan Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card">
                                                            <div class="card-body" id="modalContent">
                                                                <!-- Modal content will be dynamically generated here -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Pagination and Search Controls -->
                                    <div class="d-flex justify-content-between mt-3">
                                        <div style="margin-left: 20px;">
                                            <!--   <label for="recordsPerPage">Records per page:</label> -->
                                            <select id="recordsPerPage" class="form-select form-select-sm">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                            </select>
                                        </div>
                                        <div style="margin-right: 20px;">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination pagination-sm">
                                                    <li class="page-item">
                                                        <a class="page-link btn btn-xs btn-dark" href="#" id="prevPage">
                                                            Previous
                                                        </a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link btn btn-xs btn-primary" href="#" id="nextPage">
                                                            Next
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <!--/ Striped Rows -->
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , made with ❤️ by
                                <a href="https://ThemeSelection.com" target="_blank" class="footer-link fw-bolder">E-bidir</a>
                            </div>
                            <div>
                                <a href="https://ThemeSelection.com/license/" class="footer-link me-4" target="_blank">License</a>
                                <a href="https://ThemeSelection.com/" target="_blank" class="footer-link me-4">More
                                    Themes</a>

                                <a href="https://ThemeSelection.com/demo/ThemeSelection-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>

                                <a href="https://github.com/ThemeSelection/ThemeSelection-html-admin-template-free/issues" target="_blank" class="footer-link me-4">Support</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
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
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>
    <script src="../assets/js/mark-Notification-read.js"></script>
    <script src="../assets/js/populateuserlistbranch.js"></script>

    <script src="../assets/js/tablefunctionalities.js">
        // JavaScript for pagination and search functionality
    </script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
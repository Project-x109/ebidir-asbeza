<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
include "../common/head.php";
?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php
            include "../common/sidebar.php";
            ?>
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

                        <div class="row">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">All Transactions</h5>
                                    <div class="table-responsive text-nowrap ms-3 me-3">
                                        <table class="table table-striped" id="table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="sortable" data-column="userName">Transaction ID</th>
                                                    <th>Date</th>
                                                    <th>User ID</th>
                                                    <th>User Name</th>
                                                    <th>Transaction Type</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--/ Striped Rows -->
                        </div>



                        <!-- Modal Structure (empty modal) -->
                        <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
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

                        <!-- Status Update Modal -->
                        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Status update radio buttons will be dynamically generated here -->
                                        <div id="statusRadioContainer">
                                            <!-- Radio buttons will be generated here dynamically using JavaScript -->
                                        </div>
                                    </div>
                                    <input type="hidden" id="loanID">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="saveStatus()">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->
                    <?php
                    include "../common/footer.php";
                    ?>
                    <script src="../assets/js/jquery-3.7.0.js"></script>
                    <script src="../assets/js/jquery.dataTables.min.js"></script>

                    <script>
                        new DataTable('#table-striped');
                    </script>

                    <!-- Vendors JS -->
                    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
                    <!-- Page JS -->
                    <script src="../assets/js/dashboards-analytics.js"></script>
                    <script src="../assets/js/branchtrasaction.js"></script>
</body>

</html>
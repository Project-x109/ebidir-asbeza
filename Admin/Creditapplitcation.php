<?php
include "../connect.php";
session_start();
include "../AdminCommons/head.php";
include "./AuthorizationAdmin.php";


?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php
            include "../AdminCommons/sidebar.php";

            ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php
                include "../AdminCommons/nav.php";

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
                                    <h5 class="card-header">Lists of Loans</h5>
                                    <div class="table-responsive text-nowrap ms-3">
                                        <table class="table table-striped" id="table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="sortable" data-column="userName">User Name</th>
                                                    <th>User ID</th>
                                                    <th>Credit Limit</th>
                                                    <th>Credit Score</th>
                                                    <th>Age</th>
                                                    <th>TIN Number</th>
                                                    <th>Loan Amount</th>
                                                    <th>Status</th>
                                                    <th>Requsted Date</th>
                                                    <th>Email</th>
                                                    <th>Phone Number</th>
                                                    <th>Details</th>
                                                    <th>Update Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
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

                                        <!-- Status Update Modal -->

                                        <div class="modal fade" id="statusModal" aria-labelledby="statusModalLabel" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" id="loanID" />
                                                        <div id="statusRadioContainer">
                                                            <!-- Radio buttons will be generated here dynamically -->
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" onclick="updateStatus()">Save Changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
                                        <button type="button" class="btn btn-primary" onclick="saveStatus()">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <?php
                    include "../AdminCommons/footer.php";

                    ?>
                    <script src="../assets/js/loanrequestedlistbranch.js"></script>
                    <!-- Place this tag in your head or just before your close body tag. -->
                    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
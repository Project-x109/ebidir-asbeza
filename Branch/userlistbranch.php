<?php
include "../connect.php";
session_start();
include "./AuthorizationBranch.php";

?>
<?php
include "../BranchCommon/head.php"
?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php
            include "../common/sidebar.php";
            ?>
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
                                    <h5 class="card-header">Lists of Users</h5>
                                    <div class="table-responsive text-nowrap ms-3 me-3">
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
                                                $sql = "SELECT *,users.status as status FROM `users`";
                                                $res = $conn->query($sql);
                                                $x = 1;
                                                if ($res->num_rows > 0)
                                                    while ($row = $res->fetch_assoc()) {
                                                        $disabled = $row['status'] == 'paid' ? "disabled" : "";
                                                        echo "<tr>
                                        <td>" . ($x++) . "</td>
                                        <td>" . $row['name'] . "</td>
                                        <td>" . $row['user_id'] . "</td>
                                        <td>" . $row['TIN_Number'] . "</td>
                                        <td>" . $row['dob'] . "</td> 
                                        <td>" . $row['status'] . "</td>   
                                        <td>" . $row['email'] . "</td>   
                                        <td>" . $row['phone'] . "</td>   
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

                                </div>


                            </div>

                            <!--/ Striped Rows -->
                        </div>
                    </div>
                    <!-- / Content -->

                    <?php
                    include "../BranchCommon/footer.php"
                    ?>


                    <script src="../assets/js/populateuserlistbranch.js"></script>

                    <script src="../assets/js/tablefunctionalities.js">
                        // JavaScript for pagination and search functionality
                    </script>
                    <script src="../assets/js/jquery-3.7.0.js"></script>
                    <script src="../assets/js/jquery.dataTables.min.js"></script>

                    <script>
                        new DataTable('#table-striped');
                    </script>
</body>

</html>
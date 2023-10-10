<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";

?>
<?php
include "../common/head.php";
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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User/</span>User List</h4>

                        <div class="row">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">Lists of Users</h5>
                                    <div class="table-responsive text-nowrap ms-3 me-3">
                                        <table class="table table-striped" id="table-striped">
                                            <?php
                                            $sql = "SELECT *,users.status as status FROM `users` where role='user'";
                                            $result = $conn->query($sql);
                                            ?>
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Image</th>
                                                    <th>User Full Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Email</th>
                                                    <th>TIN Number</th>
                                                    <th>Date of Birth</th>
                                                    <th>Status</th>
                                                    <th>Credit Limit</th>
                                                    <th>Level</th>
                                                    <th>Created On</th>
                                                    <th>User Id</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                <?php
                                                // Loop through the database results and generate table rows
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr id='row-{$row['id']}'>";
                                                    echo "<td>{$row['id']}</td>";
                                                    // Assuming the 'profile' column contains image URLs
                                                    echo "<td>
                          <ul class='list-unstyled users-list m-0 avatar-group d-flex align-items-center'>
                            <li
                              data-bs-toggle='tooltip'
                              data-popup='tooltip-custom'
                              data-bs-placement='top'
                              class='avatar avatar-xs pull-up'
                              title='{$row['name']}'
                            >
                          <img src='{$row['profile']}' alt='Profile Image' class='rounded-circle'>
                            </li>
                            </ul>
                          </td>";
                                                    echo "<td>{$row['name']}</td>";
                                                    echo "<td>{$row['phone']}</td>";
                                                    echo "<td>{$row['email']}</td>";
                                                    echo "<td>{$row['TIN_Number']}</td>";
                                                    echo "<td>{$row['dob']}</td>";
                                                    $status = $row['status'];
                                                    $badgeClass = '';

                                                    if ($status === 'active') {
                                                        $badgeClass = 'success';
                                                    } elseif ($status === 'inactive') {
                                                        $badgeClass = 'danger';
                                                    } elseif ($status === 'waiting') {
                                                        $badgeClass = 'info';
                                                    } else {
                                                        $badgeClass = 'warning';
                                                    }
                                                    echo "<td><span class=\"badge bg-label-$badgeClass me-1\">$status</span></td>";
                                                    echo "<td>{$row['credit_limit']}</td>";
                                                    echo "<td>{$row['level']}</td>";
                                                    echo "<td>{$row['createdOn']}</td>";
                                                    echo "<td>{$row['user_id']}</td>";

                                                    echo "</tr>";
                                                }

                                                // Close the database connection
                                                $conn->close();
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
                    <div class="container my-5">
                        <?php
                        include "../common/footer.php";
                        ?>


                        <script src="../assets/js/populateuserlistbranch.js"></script>
                        <script src="../assets/js/jquery-3.7.0.js"></script>
                        <script src="../assets/js/jquery.dataTables.min.js"></script>

                        <script>
                            new DataTable('#table-striped');
                        </script>
</body>

</html>
<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('branch', 'delivery'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);

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
                            <?php
                            $recordsPerPageOptions = array(5, 10, 25, 50, 100);
                            $defaultRecordsPerPage = 10;
                            $recordsPerPage = isset($_GET['recordsPerPage']) && in_array($_GET['recordsPerPage'], $recordsPerPageOptions)
                                ? intval($_GET['recordsPerPage']) : $defaultRecordsPerPage;

                            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                            $offset = ($page - 1) * $recordsPerPage;
                            $search = isset($_GET['search']) ? $_GET['search'] : '';

                            // Construct the count query based on search criteria
                            $countQuery = "SELECT COUNT(*) as total FROM `users` WHERE role='user'";

                            // Check if a search is active
                            if (!empty($_GET['search'])) {
                                $search = $_GET['search'];
                                $countQuery .= " AND (name LIKE '%$search%' OR phone LIKE '%$search%' OR user_id LIKE '%$search%' OR email LIKE '%$search%' OR TIN_Number LIKE '%$search%' OR dob LIKE '%$search%' OR status LIKE '%$search%' OR credit_limit LIKE '%$search%' OR level LIKE '%$search%' OR createdOn LIKE '%$search%')";
                            }

                            $countResult = $conn->query($countQuery);
                            $totalRecords = $countResult->fetch_assoc()['total'];

                            $sql = "SELECT *, users.status as status FROM `users` WHERE role='user'";

                            // Check if a search is active
                            if (!empty($_GET['search'])) {
                                $search = $_GET['search'];
                                $sql .= " AND (name LIKE '%$search%' OR phone LIKE '%$search%' OR user_id LIKE '%$search%' OR email LIKE '%$search%' OR TIN_Number LIKE '%$search%' OR dob LIKE '%$search%' OR status LIKE '%$search%' OR credit_limit LIKE '%$search%' OR level LIKE '%$search%' OR createdOn LIKE '%$search%')";
                            }

                            $sql .= " LIMIT ?, ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ii", $offset, $recordsPerPage);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            ?>
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">Lists of Users</h5>
                                    <div class="table-responsive text-nowrap ms-3 me-3">
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-md-0">
                                                <div class="row records-per-page-search">
                                                    <div class="col-sm-4 d-flex align-items-center">
                                                        <!-- Added a class to align items vertically -->
                                                        <label for="recordsPerPage" class="col-sm-2 col-form-label me-3">Show</label>
                                                        <div class="col-sm-6 me-2">
                                                            <select class="form-select w-100" id="recordsPerPage" name="recordsPerPage" onchange="changeRecordsPerPage(this.value)">
                                                                <?php
                                                                foreach ($recordsPerPageOptions as $option) {
                                                                    $selected = ($option == $recordsPerPage) ? 'selected' : '';
                                                                    echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <label for="recordsPerPage" class="col-form-label col-sm-4">entries</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <form class="row mb-3">
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <button type="submit" class="btn btn-primary">Search</button>
                                                            <input type="text" class="form-control" placeholder="Search by Name, Phone, Email, etc." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                                            <a href="?page=<?php echo $page; ?>&recordsPerPage=<?php echo $recordsPerPage; ?>" class="btn btn-outline-secondary">Clear Search</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <table class="table table-striped" id="table-stripedmain">
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
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                <?php
                                                // Loop through the database results and generate table rows
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>{$row['user_id']}</td>";
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

                                                    echo "</tr>";
                                                }

                                                // Close the database connection
                                                $conn->close();
                                                ?>
                                            </tbody>
                                        </table>

                                        <?php
                                        echo '<nav aria-label="Page navigation" class="justify-content-center ms-5">';
                                        echo '<ul class="pagination">';
                                        echo '<li class="page-item prev ' . ($page == 1 ? 'disabled' : '') . '">';
                                        echo '<a class="page-link" href="?page=' . ($page - 1) . '&recordsPerPage=' . $recordsPerPage . '&search=' . urlencode($search) . '"><i class="tf-icon bx bx-chevrons-left"></i></a>';
                                        echo '</li>';

                                        for ($i = 1; $i <= ceil($totalRecords / $recordsPerPage); $i++) {
                                            $activeClass = ($i == $page) ? 'active' : '';
                                            echo '<li class="page-item ' . $activeClass . '">';
                                            echo '<a class="page-link" href="?page=' . $i . '&recordsPerPage=' . $recordsPerPage . '&search=' . urlencode($search) . '">' . $i . '</a>';
                                            echo '</li>';
                                        }

                                        echo '<li class="page-item next ' . ($page == ceil($totalRecords / $recordsPerPage) ? 'disabled' : '') . '">';
                                        echo '<a class="page-link" href="?page=' . ($page + 1) . '&recordsPerPage=' . $recordsPerPage . '&search=' . urlencode($search) . '"><i class="tf-icon bx bx-chevrons-right"></i></a>';
                                        echo '</li>';
                                        echo '</ul>';
                                        echo '</nav>';
                                        ?>
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


                        <script src="../assets/js/jquery-3.7.0.js"></script>
                        <script src="../assets/js/jquery.dataTables.min.js"></script>
                        <script>
                            function changeRecordsPerPage(value) {
                                window.location.href = '?page=1&recordsPerPage=' + value;
                            }
                        </script>
                        <script>
                            $(document).ready(function() {
                                $("#table-stripedmain").DataTable({
                                    "paging": false,
                                    "searching": false
                                })
                            });
                        </script>

                        <script>
                            new DataTable('#table-striped');
                        </script>
</body>

</html>
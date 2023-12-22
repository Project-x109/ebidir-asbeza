<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('Admin', 'EA'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
$_SESSION['token'] = bin2hex(random_bytes(35));
?>
<?php
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
                    <div class="loader" id="loader">
                        <div class="loader-content">
                            <div class="spinner"></div>
                        </div>
                    </div>
                    <div class="container-xxl flex-grow-1  container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Branch/</span>Branchs List</h4>
                        <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" id="error-toast">
                            <div class="toast-header">
                                <i class="bx bx-bell me-2"></i>
                                <div class="me-auto toast-title fw-semibold">Error</div>
                                <small></small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body"></div>
                        </div>

                        <div class="row">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">Lists of Branches</h5>
                                    <div class="table-responsive text-nowrap ms-3 me-3">
                                        <?php
                                        $recordsPerPageOptions = array(5, 10, 25, 50, 100);
                                        $defaultRecordsPerPage = 10;
                                        $recordsPerPage = isset($_GET['recordsPerPage']) && in_array($_GET['recordsPerPage'], $recordsPerPageOptions)
                                            ? intval($_GET['recordsPerPage']) : $defaultRecordsPerPage;

                                        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                                        $offset = ($page - 1) * $recordsPerPage;
                                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                                        if (!empty($search)) {
                                            $sql = "SELECT u.*, b.branch_name, b.location, b.branch_id 
                                            FROM users AS u
                                            JOIN branch AS b ON u.user_id = b.branch_id
                                            WHERE (u.role = 'branch' OR u.role='delivery') AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE  ? 
                                            OR u.status LIKE ? OR u.createdOn LIKE ? OR u.user_id LIKE ? OR b.location LIKE?)
                                            LIMIT ?, ?";
                                            $stmt = $conn->prepare($sql);
                                            $searchParam = "%$search%";
                                            $stmt->bind_param("sssssssii", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $offset, $recordsPerPage);
                                        } else {
                                            $sql = "SELECT u.*, b.branch_name, b.location, b.branch_id 
                                            FROM users AS u
                                            JOIN branch AS b ON u.user_id = b.branch_id
                                            WHERE u.role = 'branch' OR u.role='delivery'
                                            LIMIT ?, ?";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bind_param("ii", $offset, $recordsPerPage);
                                        }
                                        if (!empty($search)) {
                                            $countQuery = "SELECT COUNT(*) as total 
                                            FROM users AS u
                                            JOIN branch AS b ON u.user_id = b.branch_id
                                            WHERE (u.role = 'branch' OR u.role='delivery') 
                                            AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ? OR u.status LIKE ? OR u.createdOn LIKE ? OR u.user_id LIKE ? OR b.location LIKE ?)";
                                            $stmtCount = $conn->prepare($countQuery);
                                            $searchParam = "%$search%";
                                            $stmtCount->bind_param("sssssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
                                            $stmtCount->execute();
                                            $totalRecords = $stmtCount->get_result()->fetch_assoc()['total'];
                                        } else {
                                            $countQuery = "SELECT COUNT(*) as total FROM users AS u
                                            JOIN branch AS b ON u.user_id = b.branch_id
                                            WHERE (u.role = 'branch' OR u.role='delivery') ";
                                            $countResult = $conn->query($countQuery);
                                            $totalRecords = $countResult->fetch_assoc()['total'];
                                        }




                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        ?>
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-md-0">
                                                <div class="row records-per-page-search">
                                                    <div class="col-sm-4 d-flex align-items-center"> <!-- Added a class to align items vertically -->
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
                                                            <input type="text" class="form-control" placeholder="Search by Name, Email, Phone, etc." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                                            <a href="?page=<?php echo $page; ?>&recordsPerPage=<?php echo $recordsPerPage; ?>" class="btn btn-outline-secondary">Clear Search</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <table class="table table-striped" id="table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Branch Name</th>
                                                    <th>Branch ID</th>
                                                    <th>Location</th>
                                                    <th>Email</th>
                                                    <th>Phone Number</th>
                                                    <th>Status</th>
                                                    <th>Created On</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                <?php
                                                $x = 0;
                                                // Loop through the database results and generate table rows
                                                while ($row = $result->fetch_assoc()) {
                                                    $x++;
                                                    echo "<tr id='row-{$row['user_id']}'>";
                                                    echo "<td>{$row['user_id']}</td>";
                                                    // Assuming the 'profile' column contains image URLs
                                                    echo "<td>{$row['name']}</td>";
                                                    echo "<td>{$row['branch_id']}</td>";
                                                    echo "<td>{$row['location']}</td>";
                                                    echo "<td>{$row['email']}</td>";
                                                    echo "<td>{$row['phone']}</td>";

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
                                                    $user = $row['user_id'];
                                                    echo "<td><span class=\"badge bg-label-$badgeClass me-1\">$status</span></td>";
                                                    echo "<td>{$row['createdOn']}</td>";
                                                ?>
                                                    <td>
                                                        <div class='dropdown'>
                                                            <button type='button' class='btn p-0 dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                                                                <i class='bx bx-dots-vertical-rounded'></i>
                                                            </button>
                                                            <div class='dropdown-menu'>
                                                                <a class='dropdown-item' href='javascript:void(0);' onclick="editUser('<?= $user ?>')">
                                                                    <i class='bx bx-edit-alt me-1'></i> Edit
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    </tr>
                                                <?php
                                                }

                                                // Close the database connection
                                                $conn->close();
                                                ?>
                                            </tbody>
                                        </table>

                                        <?php
                                        if ($result) {
                                            echo '<nav aria-label="Page navigation" class="justify-content-center ms-5">';
                                            echo '<ul class="pagination">';

                                            // Previous page link
                                            echo '<li class="page-item prev ' . ($page == 1 ? 'disabled' : '') . '">';
                                            echo '<a class="page-link" href="?page=' . ($page - 1) . '&recordsPerPage=' . $recordsPerPage . '&search=' . urlencode($search) . '"><i class="tf-icon bx bx-chevrons-left"></i></a>';
                                            echo '</li>';

                                            // Display up to 5 page numbers with ellipsis
                                            $maxPagesToShow = 5;
                                            $startPage = max(1, $page - floor($maxPagesToShow / 2));
                                            $endPage = min(ceil($totalRecords / $recordsPerPage), $startPage + $maxPagesToShow - 1);

                                            for ($i = $startPage; $i <= $endPage; $i++) {
                                                $activeClass = ($i == $page) ? 'active' : '';
                                                echo '<li class="page-item ' . $activeClass . '">';
                                                echo '<a class="page-link" href="?page=' . $i . '&recordsPerPage=' . $recordsPerPage . '&search=' . urlencode($search) . '">' . $i . '</a>';
                                                echo '</li>';
                                            }

                                            // Display ellipsis and last page link
                                            if ($endPage < ceil($totalRecords / $recordsPerPage)) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                                echo '<li class="page-item">';
                                                echo '<a class="page-link" href="?page=' . ceil($totalRecords / $recordsPerPage) . '&recordsPerPage=' . $recordsPerPage . '&search=' . urlencode($search) . '">' . ceil($totalRecords / $recordsPerPage) . '</a>';
                                                echo '</li>';
                                            }

                                            // Next page link
                                            echo '<li class="page-item next ' . ($page == ceil($totalRecords / $recordsPerPage) ? 'disabled' : '') . '">';
                                            echo '<a class="page-link" href="?page=' . ($page + 1) . '&recordsPerPage=' . $recordsPerPage . '&search=' . urlencode($search) . '"><i class="tf-icon bx bx-chevrons-right"></i></a>';
                                            echo '</li>';

                                            echo '</ul>';
                                            echo '</nav>';
                                        } else {
                                            echo '<div>No records found</div>';
                                        }
                                        ?>

                                        <div class="modal fade" id="backDropModal" data-bs-backdrop="static" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form class="modal-content">

                                                    <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="backDropModalTitle">Update User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3">
                                                                <label for="branchnameBackdrop" class="form-label">Branch Name</label>
                                                                <input type="text" name="branchnameBackdrop" id="branchnameBackdrop" class="form-control" placeholder="Enter Branch Name" />
                                                            </div>
                                                        </div>
                                                        <div class="row g-2">
                                                            <div class="col mb-0">
                                                                <label for="emailBackdrop" class="form-label">Email</label>
                                                                <input type="text" id="emailBackdrop" name="emailBackdrop" itemid="emailBackdrop" class="form-control" placeholder="xxxx@xxx.xx" />
                                                            </div>
                                                            <div class="col mb-0">
                                                                <label for="locationBackdrop" class="form-label">Location</label>
                                                                <input type="text" id="locationBackdrop" name="locationBackdrop" itemid="locationBackdrop" class="form-control" placeholder="DD / MM / YY" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-body">


                                                        <div class="row g-2">
                                                            <div class="col mb-0">
                                                                <label for="phoneBackdrop" class="form-label">Phone Number</label>
                                                                <input type="text" id="phoneBackdrop" name="phoneBackdrop" itemid="phoneBackdrop" class="form-control" placeholder="xxxx@xxx.xx" />
                                                            </div>

                                                            <div class="col mb-0">
                                                                <label for="status" class="form-label">Status</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span id="statusspan" class="input-group-text"><i class="bx bx-buildings"></i></span>
                                                                    <select id="status" name="status" class="form-select">
                                                                        <option value="">Default select</option>
                                                                        <option value="waiting">Waiting </option>
                                                                        <option value="active">Active</option>
                                                                        <option value="inactive">Inactive</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <input type="hidden" id="userIdToUpdate" name="userIdToUpdate" />
                                                        <input type="hidden" id="userIdToUpdatebranch" name="userIdToUpdatebranch" />


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                        <button onclick="saveUser()" type="button" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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

                        <script src="../assets/js/populatebranchlist.js"></script>
                        <script src="../assets/js/jquery.dataTables.min.js"></script>


                        <script>
                            function changeRecordsPerPage(value) {
                                window.location.href = '?page=1&recordsPerPage=' + value;
                            }
                        </script>


</body>

</html>
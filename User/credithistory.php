<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('user'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
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

                    <div class="container-xxl flex-grow-1  container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User/</span>Credit History</h4>
                        <div class="row">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">Credit History</h5>
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
                                            $countQuery = "SELECT COUNT(*) as total FROM `loans` WHERE user_id='" . $_SESSION['id'] . "' AND id LIKE ? OR price LIKE ? OR status LIKE ? OR createdOn LIKE ? OR closedOn LIKE ? ";
                                            $stmtCount = $conn->prepare($countQuery);
                                            $searchParam = "%$search%";
                                            $stmtCount->bind_param("sdsss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
                                        } else {
                                            $countQuery = "SELECT COUNT(*) as total FROM `loans` WHERE user_id='" . $_SESSION['id'] . "'";
                                            $stmtCount = $conn->prepare($countQuery);
                                        }

                                        $stmtCount->execute();
                                        $countResult = $stmtCount->get_result();
                                        $totalRecords = $countResult->fetch_assoc()['total'];

                                        if (!empty($search)) {
                                            $sql = "SELECT * FROM `loans` WHERE user_id='" . $_SESSION['id'] . "' AND id LIKE ? OR price LIKE ? OR status LIKE ? OR createdOn LIKE ? OR closedOn LIKE ? LIMIT ?, ?";
                                            $stmt = $conn->prepare($sql);
                                            $searchParam = "%$search%";
                                            $stmt->bind_param("sdsssii", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $offset, $recordsPerPage);
                                        } else {
                                            $sql = "SELECT * FROM `loans` WHERE user_id='" . $_SESSION['id'] . "' LIMIT ?, ?";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bind_param("ii", $offset, $recordsPerPage);
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

                                        <table class="table table-striped" id="table-stripedmain">
                                            <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>Loan ID</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Credit Score</th>
                                                    <th>Created On</th>
                                                    <th>Closed On</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $x = 1;
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>
                                                        <td>" . ($x++) . "</td>
                                                        <td>" . $row['id'] . "</td>
                                                        <td>" . $row['price'] . "</td>
                                                        <td>" . $row['status'] . "</td> 
                                                        <td>" . $row['credit_score'] . "</td>   
                                                        <td>" . $row['createdOn'] . "</td>
                                                        <td>" . $row['closedOn'] . "</td>";
                                                    }
                                                } else {
                                                    echo '<div>No records found</div>';
                                                }
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
                                    </div>

                                </div>
                            </div>
                            <!--/ Striped Rows -->
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
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
</body>

</html>
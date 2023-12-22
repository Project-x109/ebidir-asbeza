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
            <!-- Menu -->
            <!-- Layout container -->

            <?php
            include "../common/sidebar.php";

            ?>
            <div class="layout-page">
                <!-- Navbar -->
                <?php
                include "../common/nav.php";

                ?>

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1  container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User/</span>Repayments</h4>

                        <div class="row">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">Credit History</h5>
                                    <?php
                                    $recordsPerPageOptions = array(5, 10, 25, 50, 100);
                                    $defaultRecordsPerPage = 10;
                                    $recordsPerPage = isset($_GET['recordsPerPage']) && in_array($_GET['recordsPerPage'], $recordsPerPageOptions)
                                        ? intval($_GET['recordsPerPage']) : $defaultRecordsPerPage;

                                    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                                    $offset = ($page - 1) * $recordsPerPage;
                                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                                    if (!empty($search)) {
                                        $sql = "SELECT * FROM `transactions` INNER JOIN users ON users.user_id=transactions.user_id 
                                                WHERE  transactions.updatedBy = ? AND (users.name LIKE ? OR transactions.transaction_id LIKE ? OR transactions.loan_id LIKE ? OR 
                                                transactions.user_id LIKE ? OR transactions.loan_amount LIKE ? OR transactions.credit_limit LIKE ? 
                                                OR transactions.credit_level LIKE ? OR transactions.updatedBy LIKE ? OR transactions.updatedOn LIKE ?)
                                                LIMIT ?, ?";

                                        $stmt = $conn->prepare($sql);
                                        $searchParam = "%$search%";
                                        $stmt->bind_param("ssssssssssii", $_SESSION['id'], $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $offset, $recordsPerPage);
                                    } else {
                                        // If there is no search, modify the SQL query to fetch all records
                                        $sql = "SELECT * FROM `transactions` INNER JOIN users ON users.user_id=transactions.user_id WHERE  transactions.updatedBy = ? LIMIT ?, ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("sii", $_SESSION['id'], $offset, $recordsPerPage);
                                    }
                                    if (!empty($search)) {
                                        $countQuery = "SELECT COUNT(*) as total FROM `transactions` INNER JOIN users 
                                                        ON users.user_id = transactions.user_id 
                                                        WHERE transactions.updatedBy = ? AND (users.name LIKE ? OR transactions.transaction_id LIKE ? 
                                                        OR transactions.loan_id LIKE ? OR transactions.user_id LIKE ? OR 
                                                        transactions.loan_amount LIKE ? OR transactions.credit_limit LIKE ? 
                                                        OR transactions.credit_level LIKE ? OR transactions.updatedBy LIKE ? OR transactions.updatedOn LIKE ?)";

                                        $stmtCount = $conn->prepare($countQuery);
                                        $searchParam = "%$search%";
                                        $stmtCount->bind_param("ssssssssss", $_SESSION['id'], $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
                                        $stmtCount->execute();
                                        $totalRecords = $stmtCount->get_result()->fetch_assoc()['total'];
                                    } else {
                                        $countQuery = "SELECT COUNT(*) as total FROM `transactions` WHERE transactions.updatedBy = ?";
                                        $stmtCount = $conn->prepare($countQuery);
                                        $stmtCount->bind_param("s", $_SESSION['id']);
                                        $stmtCount->execute();
                                        $countResult = $stmtCount->get_result();
                                        $totalRecords = $countResult->fetch_assoc()['total'];
                                    }


                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $x = ($page - 1) * $recordsPerPage + 1;
                                    ?>
                                    <div class="table-responsive text-nowrap ms-3 me-3">
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-md-0">
                                                <div class="row records-per-page-search">
                                                    <div class="col-sm-4 d-flex align-items-center">
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
                                                    <th>No</th>
                                                    <th>User Name</th>
                                                    <th>User ID</th>
                                                    <th>Transaction ID</th>
                                                    <th>Loan Amount</th>
                                                    <th>Credit Limit</th>
                                                    <th>Credit Level</th>
                                                    <th>Date Paid</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $totalPages = ceil($totalRecords / $recordsPerPage);
                                                if ($res->num_rows > 0) {
                                                    while ($row = $res->fetch_assoc()) {
                                                        $disabled = $row['status'] == 'paid' ? "disabled" : "";
                                                        echo "<tr>
                                                        <td>" . ($x++) . "</td>
                                                        <td>" . $row['name'] . "</td>
                                                        <td>" . $row['user_id'] . "</td>
                                                        <td>" . $row['transaction_id'] . "</td>
                                                        <td>" . $row['loan_amount'] . "</td> 
                                                        <td>" . $row['credit_limit'] . "</td>   
                                                        <td>" . $row['credit_level'] . "</td>   
                                                        <td>" . $row['updatedOn'] . "</td>   
                                                        </tr>";
                                                    }
                                                } else {
                                                    echo '<div>No records found</div>';
                                                }

                                                $stmt->close();
                                                ?>
                                            </tbody>
                                        </table>
                                        <?php
                                        if ($res) {
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
                            $(document).ready(function() {
                                $("#table-striped").DataTable({
                                    "paging": false,
                                    "searching": false
                                })
                            });
                        </script>

                        <!-- Page JS -->
</body>

</html>
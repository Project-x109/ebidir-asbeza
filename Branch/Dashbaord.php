<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('branch', 'delivery'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
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
                        <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
                            <div class="card">
                                <div class="row row-bordered g-0">
                                    <div class="col-md-8">
                                        <h5 class="card-header m-0 me-2 pb-3">Credit Status</h5>
                                        <div id="totalRevenueChart" class="px-2"></div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <!-- Remove static options and add an id for the select element -->
                                                <select class="form-select" id="growthReportSelect" aria-label="Select a year"></select>
                                            </div>
                                        </div>
                                        <!-- Add an empty div with an id for rendering the chart -->
                                        <div id="growthChart"></div>
                                        <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                            <!-- <div class="text-center">
                                                <select class="form-select" id="statusSelect" aria-label="Select a status">
                                                <option value="all">All</option>
                                                    <option value="paid">Paid</option>
                                                    <option value="unpaid">Unpaid</option>
                                                    <option value="pending">Pending</option>
                                                </select>
                                            </div> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--/ Total Revenue -->

                        <div class="row">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">Over View of User Credits</h5>
                                    <div class="table-responsive text-nowrap  ms-3 me-3">
                                        <?php
                                        $recordsPerPageOptions = array(5, 10, 25, 50, 100);
                                        $defaultRecordsPerPage = 10;
                                        $recordsPerPage = isset($_GET['recordsPerPage']) && in_array($_GET['recordsPerPage'], $recordsPerPageOptions)
                                            ? intval($_GET['recordsPerPage']) : $defaultRecordsPerPage;

                                        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                                        $offset = ($page - 1) * $recordsPerPage;

                                        $search = isset($_GET['search']) ? $_GET['search'] : '';

                                        if (!empty($search)) {
                                            $countQuery = "SELECT COUNT(*) as total FROM (
                                                SELECT 
                                                    u.user_id AS user_id, 
                                                    u.name AS user_name, 
                                                    u.credit_limit AS user_credit_limit, 
                                                    u.level AS user_credit_level, 
                                                    SUM(l.price) AS total_loan_amount,
                                                    SUM(CASE WHEN l.status = 'paid' THEN l.price ELSE 0 END) AS total_paid_amount,
                                                    SUM(CASE WHEN l.status = 'pending' THEN l.price ELSE 0 END) AS total_pending_amount,
                                                    SUM(CASE WHEN l.status != 'paid' AND l.status != 'pending' THEN l.price ELSE 0 END) AS total_unpaid_amount,
                                                    b.branch_name AS branch_name,
                                                    b.location AS branch_location
                                                FROM users AS u
                                                LEFT JOIN loans AS l ON u.user_id = l.user_id
                                                LEFT JOIN branch AS b ON l.provider = b.branch_id
                                                WHERE u.role = 'user' AND l.provider = '" . $_SESSION['id'] . "' AND u.name LIKE ? OR l.user_id LIKE ? 
                                                GROUP BY u.user_id, u.name, u.credit_limit, u.level, b.branch_name, b.location
                                            ) AS subquery";
                                            $stmtCount = $conn->prepare($countQuery);
                                            $searchParam = "%$search%";
                                            $stmtCount->bind_param("ss", $searchParam, $searchParam);
                                        } else {
                                            $countQuery = "SELECT COUNT(*) as total FROM (
                                                                SELECT 
                                                                    u.user_id AS user_id, 
                                                                    u.name AS user_name, 
                                                                    u.credit_limit AS user_credit_limit, 
                                                                    u.level AS user_credit_level, 
                                                                    SUM(l.price) AS total_loan_amount,
                                                                    SUM(CASE WHEN l.status = 'paid' THEN l.price ELSE 0 END) AS total_paid_amount,
                                                                    SUM(CASE WHEN l.status = 'pending' THEN l.price ELSE 0 END) AS total_pending_amount,
                                                                    SUM(CASE WHEN l.status != 'paid' AND l.status != 'pending' THEN l.price ELSE 0 END) AS total_unpaid_amount,
                                                                    b.branch_name AS branch_name,
                                                                    b.location AS branch_location
                                                                FROM users AS u
                                                                LEFT JOIN loans AS l ON u.user_id = l.user_id
                                                                LEFT JOIN branch AS b ON l.provider = b.branch_id
                                                                WHERE u.role = 'user' AND l.provider = '" . $_SESSION['id'] . "'
                                                                GROUP BY u.user_id, u.name, u.credit_limit, u.level, b.branch_name, b.location
                                                            ) AS subquery";
                                            $stmtCount = $conn->prepare($countQuery);
                                        }

                                        $stmtCount->execute();
                                        $countResult = $stmtCount->get_result();
                                        $totalRecords = $countResult->fetch_assoc()['total'];

                                        if (!empty($search)) {
                                            $sql = "SELECT 
                                                    u.user_id AS user_id, 
                                                    u.name AS user_name, 
                                                    u.credit_limit AS user_credit_limit, 
                                                    u.level AS user_credit_level, 
                                                    SUM(CASE WHEN l.user_id = u.user_id AND l.provider = '" . $_SESSION['id'] . "' THEN l.price ELSE 0 END) AS total_loan_amount,
                                                    SUM(CASE WHEN l.status = 'paid' AND l.user_id = u.user_id AND l.provider = '" . $_SESSION['id'] . "' THEN l.price ELSE 0 END) AS total_paid_amount,
                                                    SUM(CASE WHEN l.status = 'pending'  AND l.user_id = u.user_id AND l.provider = '" . $_SESSION['id'] . "' THEN l.price ELSE 0 END) AS total_pending_amount,
                                                    SUM(CASE WHEN l.status != 'paid' AND l.status != 'pending' AND l.user_id = u.user_id AND l.provider = '" . $_SESSION['id'] . "' THEN l.price ELSE 0 END) AS total_unpaid_amount,
                                                    b.branch_name AS branch_name,
                                                    b.location AS branch_location
                                                FROM users AS u
                                                LEFT JOIN loans AS l ON u.user_id = l.user_id
                                                LEFT JOIN branch AS b ON l.provider = b.branch_id
                                                WHERE u.role = 'user' AND l.provider = '" . $_SESSION['id'] . "'  AND u.name LIKE ? OR u.user_id LIKE ? 
                                                GROUP BY u.user_id, u.name, u.credit_limit, u.level, b.branch_name, b.location 
                                                ORDER BY u.user_id
                                                LIMIT ?, ?";
                                            $stmt = $conn->prepare($sql);
                                            $searchParam = "%$search%";
                                            $stmt->bind_param("ssii", $searchParam, $searchParam, $offset, $recordsPerPage);
                                        } else {
                                            $sql = "SELECT 
                                                        u.user_id AS user_id, 
                                                        u.name AS user_name, 
                                                        u.credit_limit AS user_credit_limit, 
                                                        u.level AS user_credit_level, 
                                                        SUM(l.price) AS total_loan_amount,
                                                        SUM(CASE WHEN l.status = 'paid' THEN l.price ELSE 0 END) AS total_paid_amount,
                                                        SUM(CASE WHEN l.status = 'pending' THEN l.price ELSE 0 END) AS total_pending_amount,
                                                        SUM(CASE WHEN l.status != 'paid' AND l.status != 'pending' THEN l.price ELSE 0 END) AS total_unpaid_amount,
                                                        b.branch_name AS branch_name,
                                                        b.location AS branch_location
                                                    FROM users AS u
                                                    LEFT JOIN loans AS l ON u.user_id = l.user_id
                                                    LEFT JOIN branch AS b ON l.provider = b.branch_id
                                                    WHERE u.role = 'user' AND l.provider = '" . $_SESSION['id'] . "'
                                                    GROUP BY u.user_id, u.name, u.credit_limit, u.level, b.branch_name, b.location
                                                    ORDER BY u.user_id
                                                    LIMIT ?, ?";
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
                                                <form class="row mb-3" method="GET" action="">
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <button type="submit" class="btn btn-primary">Search</button>
                                                            <input type="text" class="form-control" placeholder="Search by Name, Email, Phone, etc." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                                            <input type="hidden" name="page" value="<?php echo $page; ?>">
                                                            <input type="hidden" name="recordsPerPage" value="<?php echo $recordsPerPage; ?>">
                                                            <a href="?page=<?php echo $page; ?>&recordsPerPage=<?php echo $recordsPerPage; ?>" class="btn btn-outline-secondary">Clear Search</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <table class="table table-striped" id="table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Account Name</th>
                                                    <th>ID</th>
                                                    <th>Total Loan </th>
                                                    <th>Total Loan Paid</th>
                                                    <th>Total Loan Pending</th>
                                                    <th>Total Loan Unpaid</th>
                                                    <th>Credit Limit</th>
                                                    <th>Level</th>
                                                    <!--  <th>Branch Name(Location)</th> -->
                                                    <th>Detail</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<tr>';
                                                        echo '<td>' . $row['user_name'] . '</td>';
                                                        echo '<td>' . $row['user_id'] . '</td>';
                                                        echo '<td>' . $row['total_loan_amount'] . '</td>';
                                                        echo '<td>' . $row['total_paid_amount'] . '</td>';
                                                        echo '<td>' . $row['total_pending_amount'] . '</td>';
                                                        echo '<td>' . $row['total_unpaid_amount'] . '</td>';
                                                        echo '<td>' . $row['user_credit_limit'] . '</td>';
                                                        echo '<td>' . $row['user_credit_level'] . '</td>';
                                                        /* echo '<td>' . $row['branch_name'] . ' (' . $row['branch_location'] . ')</td>'; */
                                                        echo '<td><a href="userdetail.php?user_id=' . $row['user_id'] . '">Detail</a></td>'; // Replace 'user_detail.php' with the actual URL
                                                        echo '</tr>';
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

                        <div class="row">
                            <!-- Order Statistics -->
                            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0 me-2">Credit Snapshot</h5>
                                            <small class="text-muted" id="todaysdate"></small>
                                        </div>
                                        <!--  <div class="dropdown">
                                            <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <h2 class="mb-2" id="creditScore">0</h2>
                                                <span>Total Loaned</span>
                                            </div>
                                            <div id="orderStatisticsChart"></div>
                                        </div>
                                        <ul class="p-0 m-0">
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Paid</h6>
                                                        <small class="text-muted">Payment Total</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold" id="paidLoanAmount">82.5k</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-success"><i class="bx bx-closet"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Unpaid</h6>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold" id="unpaidLoanAmount">23.8k</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-home-alt"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Pending</h6>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold" id="pendingLoanAmount">0</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-football"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Total</h6>
                                                        <small class="text-muted">For all accounts</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold" id="totalforll">0</small>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--/ Order Statistics -->

                            <!-- Expense Overview -->
                            <div class="col-md-6 col-lg-4 order-1 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <ul class="nav nav-pills" role="tablist">
                                            <li class="nav-item">
                                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-tabs-line-card-income" aria-controls="navs-tabs-line-card-income" aria-selected="true">
                                                    Six month Progress
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-tabs-line-card-expenses" aria-controls="navs-tabs-line-card-expenses">Report

                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="card-body px-0">
                                        <div class="tab-content p-0">
                                            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                                                <div class="d-flex p-4 pt-3">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="../assets/img/icons/unicons/wallet.png" alt="User" />
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Total six month loan</small>
                                                        <div class="d-flex align-items-center">
                                                            <h6 class="mb-0 me-1" id="totalOriginalAmount"></h6>
                                                            <small class=" fw-semibold">
                                                                <i id="percentageChange"></i>

                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="incomeChart"></div>
                                                <div class="d-flex justify-content-center pt-4 gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div id="expensesOfWeek"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade show" id="navs-tabs-line-card-expenses" role="tabpanel">
                                                <div class="d-flex p-4 pt-3">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="../assets/img/icons/unicons/wallet.png" alt="User" />
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Loan Status</small>
                                                    </div>
                                                </div>
                                                <canvas id="pieChart" style="margin-top: -50px;" width="400" height="100"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Expense Overview -->

                            <!-- Transactions -->
                            <div class="col-md-6 col-lg-4 order-2 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title m-0 me-2">Repayment Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="p-0 m-0">
                                            <?php
                                            $images = [
                                                "../assets/img/icons/unicons/cc-success.png",
                                                "../assets/img/icons/unicons/wallet.png",
                                                "../assets/img/icons/unicons/chart.png",
                                                "../assets/img/icons/unicons/cc-success.png",
                                                "../assets/img/icons/unicons/cc-warning.png",
                                            ];
                                            shuffle($images);
                                            $user_id = $_SESSION['id'];

                                            $query = "SELECT t.*, u.*
                                                FROM transactions t
                                                JOIN users u ON t.user_id = u.user_id
                                                WHERE updatedBy = ?
                                                ORDER BY t.updatedOn DESC
                                                LIMIT 5";

                                            // Use a prepared statement to avoid SQL injection
                                            $stmt = $conn->prepare($query);
                                            $stmt->bind_param("s", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            if ($result->num_rows > 0) {
                                                $i = 0;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<li class="d-flex mb-4 pb-1">';
                                                    echo '<div class="avatar flex-shrink-0 me-3">';
                                                    echo '        <img src="' . $images[$i] . '" alt="User" class="rounded" />';
                                                    echo '</div>';
                                                    echo '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">';
                                                    echo '<div class="me-2">';
                                                    echo '<small class="text-muted d-block mb-1">' . $row['name'] . '</small>';
                                                    echo '<h6 class="mb-0">' . $row['credit_level'] . '</h6>';
                                                    echo '</div>';
                                                    echo '<div class="user-progress d-flex align-items-center gap-1">';
                                                    echo '<h6 class="mb-0">+' . $row['loan_amount'] . '</h6>';
                                                    echo '<span class="text-muted">ETB</span>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                    echo '</li>';
                                                    $i++;
                                                }
                                            } else {
                                                echo "No transactions found";
                                            }

                                            // Close the prepared statement
                                            $stmt->close();
                                            ?>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <!--/ Transactions -->
                        </div>
                    </div>
                    <div class="container my-5">
                        <?php
                        include "../common/footer.php";
                        ?>
                        <script>
                            $(document).ready(function() {
                                $("#table-striped").DataTable({
                                    "paging": false,
                                    "searching": false
                                })
                            });
                        </script>
                        <script>
                            function changeRecordsPerPage(value) {
                                window.location.href = '?page=1&recordsPerPage=' + value;
                            }
                        </script>
                        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
                        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

                        <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
                        <script src="../assets/js/branchtotalRevenueChart1.js"></script>
                        <script src="../assets/js/linegraphp.js"></script>
                        <script src="../assets/js/chartdount.js"></script>

                        <script async defer src="https://buttons.github.io/buttons.js"></script>
                        <script src="../assets/js/branchgrowthchart.js"></script>
                        <script src="../assets/js/branchpicahrt1.js"></script>


</body>

</html>
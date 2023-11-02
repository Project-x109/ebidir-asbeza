<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('branch','delivery'); // Define the required roles for the specific page
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
                                                    <th>Branch Name(Location)</th>
                                                    <th>Detail</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                <?php
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
                                                WHERE u.role = 'user' AND b.branch_id = '" . $_SESSION['id'] . "'
                                                GROUP BY u.user_id, u.name, u.credit_limit, u.level, b.branch_name, b.location
                                                ORDER BY u.user_id";

                                                $result = $conn->query($sql);
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
                                                        echo '<td>' . $row['branch_name'] . ' (' . $row['branch_location'] . ')</td>';
                                                        echo '<td><a href="userdetail.php?user_id=' . $row['user_id'] . '">Detail</a></td>'; // Replace 'user_detail.php' with the actual URL
                                                        echo '</tr>';
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="7">No users found</td></tr>';
                                                }

                                                // Close the database connection

                                                ?>
                                            </tbody>
                                        </table>

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
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <h2 class="mb-2" id="creditScore">750</h2>
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
                                                        <small class="fw-semibold" id="pendingLoanAmount">849k</small>
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
                                                        <small class="fw-semibold" id="totalforll">99k</small>
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
                                                    <!-- <div>
                                                        <p class="mb-n1 mt-1">Expenses This Week</p>
                                                        <small class="text-muted">$39 less than last week</small>
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="tab-pane fade show" id="navs-tabs-line-card-expenses" role="tabpanel">
                                                <div class="d-flex p-4 pt-3">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="../assets/img/icons/unicons/wallet.png" alt="User" />
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Loan Status</small>
                                                        <!-- <div class="d-flex align-items-center">
                                                            <h6 class="mb-0 me-1">$459.10</h6>
                                                            <small class="text-success fw-semibold">
                                                                <i class="bx bx-chevron-up"></i>
                                                                42.9%
                                                            </small>
                                                        </div> -->
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
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                                <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="p-0 m-0">
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/paypal.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Loans Paid On
                                                            Time</small>
                                                        <h6 class="mb-0">5</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">+82.6</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Loans Past Due</small>
                                                        <h6 class="mb-0">2</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">+1,250</h6>
                                                        <span class="text-muted">ETB</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/chart.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Total Repaid</small>
                                                        <h6 class="mb-0">3</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">+1,250</h6>
                                                        <span class="text-muted">ETB</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/cc-success.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Credit Card</small>
                                                        <h6 class="mb-0">Ordered Food</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">-838.71</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Wallet</small>
                                                        <h6 class="mb-0">Starbucks</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">+203.33</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/cc-warning.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Mastercard</small>
                                                        <h6 class="mb-0">Ordered Food</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">-92.45</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
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
                                $('#table-striped').DataTable();
                            });
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

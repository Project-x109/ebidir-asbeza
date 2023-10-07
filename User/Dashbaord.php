<?php
include "../connect.php";
session_start();
include "./AuthorizationUser.php";

?>

<?php

include "../UsersCommon/head.php"
?>
<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <?php
      include "../UsersCommon/sidebar.php"
      ?>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <?php

        include "../UsersCommon/nav.php"
        ?>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <?php
          $id = $_SESSION['id'];
          $sql1 = "SELECT * from users where user_id='$id'";
          $res = $conn->query($sql1);
          $row = $res->fetch_assoc();
          ?>
          <div class="container-xxl flex-grow-1  container-p-y">
            <div class="row">
              <div class="col-lg-8 mb-4 order-0">
                <div class="card">
                  <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                      <div class="card-body" id="Statusindicator">
                        <h5 class="card-title text-primary">Congratulations
                          <?php echo $row['name']; ?>! 🎉
                        </h5>
                        <p class="mb-4">Your current credit is <span class="fw-bold"><?php echo $row['credit_limit'] ?> Birr</span> Check your new level badge in
                          your profile.</p>
                        <a href="#table-striped" class="btn btn-sm btn-outline-primary">View Table</a>
                      </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                      <div class="card-body pb-0 px-0 px-md-4">
                        <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                          <div class="avatar flex-shrink-0">
                            <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded" />
                          </div>
                          <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                              <a class="dropdown-item" href="javascript:void(0);">View More</a>
                              <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                            </div>
                          </div>
                        </div>
                        <div id="CreditLimitCardID">
                          <span class="fw-semibold d-block mb-1">Credit Level</span>
                          <h5 class="card-title mb-2"><?php echo $row['level'];  ?>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> </small>
                          </h5>

                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                          <div class="avatar flex-shrink-0">
                            <img src="../assets/img/icons/unicons/wallet-info.png" alt="Credit Card" class="rounded" />
                          </div>
                          <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                              <a class="dropdown-item" href="javascript:void(0);">View More</a>
                              <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                            </div>
                          </div>
                        </div>
                        <div id="AvailableCreditID">
                          <span class="fw-semibold d-block mb-1">Available Credit</span>
                          <h5 class="card-title text-nowrap mb-2"><?php echo $_SESSION['credit_limit'] ?>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i></small>
                          </h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- Striped Rows -->
              <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                <div class="card">
                  <div class="d-flex  mt-3">
                    <div style="margin-left: 20px;">
                      <input type="text" class="form-control form-control-sm" id="tableSearch" placeholder="Search..." />
                    </div>
                  </div>
                  <h5 class="card-header">Transaction</h5>
                  <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="table-striped">
                      <thead>
                        <tr>
                          <th>Account Name</th>
                          <th>ID</th>
                          <th>Starting Credit</th>
                          <th>Total Used</th>
                          <th>Credit Left</th>
                          <th>Item Purchase Date</th>
                          <th>Credit Repayment Date</th>
                          <th>Status</th>
                          <th>Detail</th>
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
                            <h5 class="modal-title" id="modalToggleLabel">Purchase History</h5>
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
                  <div class="d-flex justify-content-between mt-3">
                    <div style="margin-left: 20px;">
                      <!--   <label for="recordsPerPage">Records per page:</label> -->
                      <select id="recordsPerPage" class="form-select form-select-sm">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                      </select>
                    </div>
                    <div style="margin-right: 20px;">
                      <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm">
                          <li class="page-item">
                            <a class="page-link btn btn-xs btn-dark" href="#" id="prevPage">
                              Previous
                            </a>
                          </li>
                          <li class="page-item">
                            <a class="page-link btn btn-xs btn-primary" href="#" id="nextPage">
                              Next
                            </a>
                          </li>
                        </ul>
                      </nav>
                    </div>
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
                      <small class="text-muted" id="todaysdate">As of August 2022</small>
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
                        <h2 class="mb-2" id="TotalCreditbystatus">750</h2>
                        <span>Total Used Credit</span>
                      </div>
                      <div id="orderStatisticsChart"></div>
                    </div>
                    <ul id="topItemsList" class="p-0 m-0">
                      <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                          <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                            <h6 class="mb-0">Credit Snapshot</h6>
                            <small class="text-muted">As of August 2022</small>
                          </div>
                          <div class="user-progress">
                            <small class="fw-semibold">82.5k</small>
                          </div>
                        </div>
                      </li>
                      <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                          <span class="avatar-initial rounded bg-label-success"><i class="bx bx-closet"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                            <h6 class="mb-0">Credit Limit</h6>
                            <small class="text-muted">Total approved limit</small>
                          </div>
                          <div class="user-progress">
                            <small class="fw-semibold">23.8k</small>
                          </div>
                        </div>
                      </li>
                      <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                          <span class="avatar-initial rounded bg-label-info"><i class="bx bx-home-alt"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                            <h6 class="mb-0">Available</h6>
                            <small class="text-muted">Unused credit amount</small>
                          </div>
                          <div class="user-progress">
                            <small class="fw-semibold">849k</small>
                          </div>
                        </div>
                      </li>
                      <li class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                          <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-football"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                            <h6 class="mb-0">Total Due</h6>
                            <small class="text-muted">For all accounts</small>
                          </div>
                          <div class="user-progress">
                            <small class="fw-semibold">99k</small>
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
                          Six Month Purchase Report
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
                            <small class="text-muted d-block">Total Expenditure</small>
                            <div class="d-flex align-items-center">
                              <h6 class="mb-0 me-1" id="totalspent"></h6>
                              <small class="text-success fw-semibold" id="totalspentchange">
                                <i class="bx bx-chevron-up"></i>

                              </small>
                            </div>
                          </div>
                        </div>
                        <div id="incomeChart"></div>
                        <div class="d-flex justify-content-center pt-4 gap-2">
                          <div class="flex-shrink-0">
                            <div id="expensesOfWeek"></div>
                          </div>
                          <div>
                            <p class="mb-n1 mt-1"> Credit Amount</p>
                            <small class="text-muted"></small>
                          </div>
                        </div>
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
                            <small class="text-muted d-block mb-1">Loans Paid On Time</small>
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
          <?php

          include "../UsersCommon/footer.php"
          ?>
          <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
          <script src="../assets/js/populatetable.js"></script>
          <script src="../assets/js/userorderstatisticschart.js"></script>
          <script src="../assets/js/mark-Notification-read.js"></script>

          <!-- <script src="../assets/js/userdashboarddata.js"></script> -->

          <script src="../assets/js/tablefunctionalities.js"></script>
</body>

</html>
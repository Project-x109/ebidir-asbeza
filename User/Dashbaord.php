<?php
include "../connect.php";
session_start();
include "../common/head.php";
include "../common/Authorization.php";

$requiredRoles = array('user'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);

?>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <?php
      include "../common/sidebar.php"
      ?>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <?php

        include "../common/nav.php"
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
                        <h5 class="card-title text-primary">Welcome
                          <?php echo $row['name']; ?>! 🎉
                        </h5>
                        <p class="mb-4">Your current credit is <span class="fw-bold"><?php echo $row['credit_limit'] ?> Birr</span> Check your new level badge in
                          your profile.</p>
                        <!-- <a href="#table-striped" class="btn btn-sm btn-outline-primary">View Table</a> -->
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
                              <!--  <i class="bx bx-dots-vertical-rounded"></i> -->
                            </button>
                            <!-- <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                              <a class="dropdown-item" href="javascript:void(0);">View More</a>
                              <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                            </div> -->
                          </div>
                        </div>
                        <div id="CreditLimitCardID">
                          <span class="fw-semibold d-block mb-1">Credit Level</span>
                          <h5 class="card-title mb-2"><?php echo $row['level'];  ?>
                            <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> </small> -->
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
                              <!--  <i class="bx bx-dots-vertical-rounded"></i> -->
                            </button>
                            <!-- <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                              <a class="dropdown-item" href="javascript:void(0);">View More</a>
                              <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                            </div> -->
                          </div>
                        </div>
                        <div id="AvailableCreditID">
                          <span class="fw-semibold d-block mb-1">Available Credit</span>
                          <h5 class="card-title text-nowrap mb-2"><?php echo $_SESSION['credit_limit'] ?>
                            <!--  <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i></small> -->
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
              <!--  <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
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


                  </div>
     
                  <div class="d-flex justify-content-between mt-3">
                    <div style="margin-left: 20px;">
                     
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


              </div> -->

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
                    <!--    <div class="dropdown">
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
                        <span>Total Used Credit</span>
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
                            <small class="fw-semibold" id="paidLoanAmount">0</small>
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
                            <small class="fw-semibold" id="unpaidLoanAmount">0</small>
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
                          Six Month Credit Report
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
                            <small class="text-muted d-block">Total Credit in the past Six month</small>
                          </div>
                        </div>
                        <div id="incomeChart"></div>
                        <div class="d-flex justify-content-center pt-4 gap-2">
                          <div class="flex-shrink-0">
                            <div id="expensesOfWeek"></div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--/ Expense Overview -->

              <!-- Transactions -->

              <?php
              $items_per_page = 4;
              $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Get the current page number
              $offset = ($page - 1) * $items_per_page;
              $user_id = $_SESSION['id'];
              $subquery = "SELECT MAX(id) AS last_loan_id FROM loans WHERE user_id = '$user_id' ";
              $sql = "SELECT c.item_name, c.item_price, c.item_image, c.item_spec, c.item_quantity, l.createdOn
                FROM cart c
                JOIN loans l ON c.loan_id = ($subquery)
                WHERE c.user_id = '$user_id' and c.loan_id=l.id
                LIMIT $items_per_page OFFSET $offset
              ";
              $result = $conn->query($sql);
              $total_items_query = "SELECT COUNT(*) AS total_items FROM cart WHERE user_id = '$user_id' AND loan_id = ($subquery)";
              $total_items_result = $conn->query($total_items_query);
              $row = $total_items_result->fetch_assoc();
              $total_items = $row['total_items'];
              ?>
              <div class="col-md-6 col-lg-4 order-2 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Latest Purchase History</h5>

                  </div>
                  <div class="card-body">
                    <ul class="p-0 m-0">
                      <?php
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          echo '<li class="d-flex mb-4 pb-1">';
                          echo '<div class="avatar flex-shrink-0 me-3">';
                          echo '<img src="' . $row['item_image'] . '" alt="Item" class="rounded" />';
                          echo '</div>';
                          echo '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">';
                          echo '<div class="me-2">';
                          echo '<small class="text-muted d-block mb-1">Item Name</small>';
                          echo '<h6 class="mb-0">' . $row['item_name'] . '</h6>';
                          echo '</div>';
                          echo '<div class="user-progress d-flex align-items-center gap-1">';
                          echo '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">';
                          echo '<div class="me-2 mt-3">';
                          echo '<h6 class="mb-0"> ' . $row['item_price'] . ' ETB' . '</h6>';
                          echo '<small class="text-muted d-block mb-1">' . 'Qty ' . $row['item_quantity'] . '</small>';
                          echo '</div';
                          echo '</div>';
                          echo '</div>';
                          echo '</li>';
                        }
                      } else {
                        echo "No cart items found.";
                      }

                      $conn->close();
                      ?>
                    </ul>
                  </div>

                  <nav aria-label="Page navigation" class="justify-content-center ms-5">
                    <ul class="pagination">
                      <li class="page-item prev">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>"><i class="tf-icon bx bx-chevrons-left"></i></a>
                      </li>
                      <?php
                      $total_pages = ceil($total_items / $items_per_page);
                      for ($i = 1; $i <= $total_pages; $i++) {
                        $activeClass = ($i == $page) ? 'active' : '';
                        echo '<li class="page-item ' . $activeClass . '">';
                        echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                      }
                      ?>
                      <li class="page-item next">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>"><i class="tf-icon bx bx-chevrons-right"></i></a>
                      </li>
                      </u>
                  </nav>

                </div>
              </div>
              <!--/ Transactions -->
            </div>
          </div>
          <div class="container my-5">
            <?php

            include "../common/footer.php";
            ?>

            <!-- <script src="../assets/js/populatetable.js"></script> -->
            <script src="../assets/js/chartdount.js"></script>
            <!-- <script src="../assets/js/userorderstatisticschart.js"></script>

            <script src="../assets/js/userdashboarddata.js"></script> -->

            <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
            <script src="../assets/js/branchpicahrt1.js"></script>
            <script src="../assets/js/linegraphp.js"></script>

</body>

</html>
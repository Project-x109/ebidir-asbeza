<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('Admin', 'EA'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
$_SESSION['token'] = bin2hex(random_bytes(35));
// Execute the query

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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User/</span>User List</h4>

            <div class="loader" id="loader">
              <div class="loader-content">
                <div class="spinner"></div>
              </div>
            </div>

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
                  <h5 class="card-header">Lists of Users</h5>
                  <div class="table-responsive text-nowrap ms-3 me-3">
                    <?php
                    $recordsPerPageOptions = array(5, 10, 25, 50, 100);
                    $defaultRecordsPerPage = 5;
                    $recordsPerPage = isset($_GET['recordsPerPage']) && in_array($_GET['recordsPerPage'], $recordsPerPageOptions)
                      ? intval($_GET['recordsPerPage'])
                      : $defaultRecordsPerPage;

                    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                    $offset = ($page - 1) * $recordsPerPage;


                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    if (!empty($search)) {
                      $sql = "SELECT * FROM users WHERE role='user' AND (name LIKE ? OR email LIKE ?
                       OR phone LIKE ? OR TIN_Number LIKE ? 
                       OR status LIKE ? OR user_id LIKE ? OR createdOn LIKE ?) LIMIT ?, ?";
                      $stmt = $conn->prepare($sql);
                      $searchParam = "%$search%";
                      $stmt->bind_param("ssssssssi", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $offset, $recordsPerPage);
                    } else {
                      // If there is no search, modify the SQL query to fetch all records
                      $sql = "SELECT * FROM users WHERE role='user' LIMIT ?, ?";
                      $stmt = $conn->prepare($sql);
                      $stmt->bind_param("ii", $offset, $recordsPerPage);
                    }
                    if (!empty($search)) {
                      $countQuery = "SELECT COUNT(*) as total FROM users WHERE role='user' AND (name LIKE ? OR email LIKE ?
                      OR phone LIKE ? OR TIN_Number LIKE ? 
                      OR status LIKE ? OR user_id LIKE ? OR createdOn LIKE ?)";
                      $stmtCount = $conn->prepare($countQuery);
                      $searchParam = "%$search%";
                      $stmtCount->bind_param("sssssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
                      $stmtCount->execute();
                      $totalRecords = $stmtCount->get_result()->fetch_assoc()['total'];
                    } else {
                      $countQuery = "SELECT COUNT(*) as total FROM users WHERE role='user'";
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

                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody class="table-border-bottom-0">

                        <?php
                        // Loop through the database results and generate table rows
                       
                        while ($row = $result->fetch_assoc()) {
                          echo "<tr id='row-{$row['user_id']}'>";
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
                          $user = $row['user_id'];

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

                        ?>
                          <td>
                            <div class='dropdown'>
                              <button type='button' class='btn p-0 dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                                <i class='bx bx-dots-vertical-rounded'></i>
                              </button>
                              <div class='dropdown-menu'>
                                <a class='dropdown-item' href='javascript:void(0);' onclick="editUser('<?= $user ?>')">

                                  <i class='bx bx-edit-alt me-1'></i> Edit</a>
                              </div>
                            </div>
                          </td>
                        <?php
                          echo "</tr>";
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

                          <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? ''; ?>">
                          <div class="modal-header">
                            <h5 class="modal-title" id="backDropModalTitle">Update User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col mb-3">
                                <label for="nameBackdrop" class="form-label">Name</label>
                                <input type="text" name="nameBackdrop" id="nameBackdrop" class="form-control" placeholder="Enter Name" />
                              </div>
                            </div>
                            <div class="row g-2">
                              <div class="col mb-0">
                                <label for="emailBackdrop" class="form-label">Email</label>
                                <input type="text" id="emailBackdrop" name="emailBackdrop" itemid="emailBackdrop" class="form-control" placeholder="xxxx@xxx.xx" />
                              </div>
                              <div class="col mb-0">
                                <label for="dobBackdrop" class="form-label">Date of Birth</label>
                                <input type="date" id="dobBackdrop" name="dobBackdrop" itemid="dobBackdrop" class="form-control" placeholder="DD / MM / YY" />
                              </div>
                            </div>
                          </div>

                          <div class="modal-body">
                            <div class="col mb-0">
                              <label for="phoneBackdrop" class="form-label">Phone Number</label>
                              <input type="text" id="phoneBackdrop" name="phoneBackdrop" itemid="phoneBackdrop" class="form-control" placeholder="xxxx@xxx.xx" />
                            </div>

                            <div class="row g-2">
                              <div class="col mb-0">
                                <label for="TIN_Number" class="form-label">TIN Number</label>
                                <input type="text" id="TIN_Number" name="TIN_Number" class="form-control" placeholder="Enter TIN Number" />
                              </div>
                              <div class="col mb-0">
                                <label for="emailBackdrop" class="form-label">Status</label>
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
            <script src="../assets/js/jquery-3.7.0.js"></script>

            <script>
              function changeRecordsPerPage(value) {
                window.location.href = '?page=1&recordsPerPage=' + value;
              }
            </script>
            <script src="../assets/js/jquery.dataTables.min.js"></script>
            <script src="../assets/js/populateuserlist.js"></script>


</body>

</html>
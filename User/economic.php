<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('user'); // Define the required roles for the specific page
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

          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span>Economic Information</h4>
            <!-- Toast with Placements -->
            <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto toast-title fw-semibold">Error</div>
                <small></small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body"></div>
            </div>
            <!-- Toast with Placements -->
            <!-- Basic Layout & Basic with Icons -->
            <div class="row">
              <!-- Basic with Icons -->
              <div class="col-xxl">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">2.Economic Information</h5>
                  </div>
                  <?php
                  $sql = "SELECT * FROM economic WHERE user_id = '" . $_SESSION['id'] . "'";
                  $sql1 = "SELECT * from users where user_id='$id'";
                  $res1 = $conn->query($sql1);
                  $row1 = $res1->fetch_assoc();
                  $res = $conn->query($sql);
                  $found = $res->num_rows;
                  $field_of_employeement = "";
                  $number_of_income = "";
                  $year = "";
                  $position = "";
                  $salary = "";
                  if ($res->num_rows) {
                    $row = $res->fetch_assoc();
                    $field_of_employeement = $row['field_of_employeement'];
                    $number_of_income = $row['number_of_income'];
                    $year = $row['year'];
                    $position = $row['position'];
                    $salary = $row['salary'];
                  }
                  ?>
                  <!-- Toast Container -->
                  <div id="toast-container" class="toast-container" aria-live="polite" aria-atomic="true"></div>
                  <div class="card-body">
                    <form id="economicForm" action="backend.php" method="POST">
                      <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                      <input type="hidden" name="add_economic" value="1">
                      <!-- <input type="hidden" name="id" value='<?php echo $_SESSION['id'] ?>' /> -->
                      <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fieldofEmployment">Field of
                          Employment :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fieldofEmployment2" class="input-group-text"><i class="bx bx-award"></i></span>
                            <input type="text" class="form-control" id="basic-icon-default-fieldofEmployment" placeholder="Engineer" aria-label="Electrical Engineer" 
                            aria-describedby="basic-icon-default-fieldofEmployment2" name="field_of_employeement" value='<?php echo $field_of_employeement ?>' />
                          </div>
                        </div>

                        <label class="col-sm-2 col-form-label" for="basic-icon-default-numberofincome">Number of Income
                          :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-numberofincome2" class="input-group-text"><i class="bx bx-archive-in"></i></span>
                            <input type="number" class="form-control" id="basic-icon-default-numberofincome" placeholder="5" aria-label="6" aria-describedby="basic-icon-default-numberofincome2" name="number_of_income" value='<?php echo $number_of_income ?>' />
                          </div>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <label for="html5-datetime-local-input" class="col-md-2 col-form-label">Year of Employment
                          :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-YearofEmployment2" class="input-group-text"><i class="bx bx-calendar"></i></span>
                            <input class="form-control" type="date" value="2021-06-18" id="html5-datetime-local-input-YearofEmployment" placeholder="5" name="year" value='<?php echo $year ?>' />
                          </div>
                        </div>
                        <label for="html5-datetime-local-input" class="col-md-2 col-form-label">Position :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-position2" class="input-group-text"><i class="bx bx-chair"></i></span>
                            <input type="text" id="basic-icon-default-position" class="form-control" placeholder="Manger" aria-label="Manger" aria-describedby="basic-icon-default-position2" name="position" value='<?php echo $position ?>' />

                          </div>

                        </div>

                      </div>


                      <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-salary">Salary :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-grid-small"></i></span>
                            <input type="text" id="basic-icon-default-salary" class="form-control" placeholder="56790" aria-label="salary" aria-describedby="basic-icon-default-salary" name="salary" value='<?php echo $salary ?>' />
                          </div>
                        </div>
                      </div>
                      <?php

                      if (!$row1 || $row1['form_done'] == 0) {
                        // Display the link only if "form_done" is not set or is 0
                      ?>
                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                            <button type="submit" id="submit-btn" name='<?php echo $found ? "update_economic" : "add_economic" ?>' class="btn btn-primary mb-4">
                              <?php echo $found ? "Update" : "Submit" ?>
                            </button>
                          </div>
                        </div>
                      <?php
                      }
                      ?>
                    </form>
                  </div>
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

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="buy-now">
        </div>
        <!-- / Content -->
        <div class="container my-5">
          <?php
          include "../common/footer.php";
          ?>


          <!-- Page JS -->
          <script src="../assets/js/ui-toasts-economic.js"></script>

</body>

</html>
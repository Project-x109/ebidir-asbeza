<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('user'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
$_SESSION['token'] = bin2hex(random_bytes(35));
?>



<?php

include "../common/head.php"
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

          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span>Personal Information</h4>
            <!-- Basic Layout & Basic with Icons -->
            <div class="row">
              <div class="col-xxl">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <?php
                    $sql = "SELECT * FROM personal WHERE user_id = '" . $_SESSION['id'] . "'";
                    $res2 = $conn->query($sql);

                    $sql1 = "SELECT * from users WHERE user_id = '" . $_SESSION['id'] . "'";
                    $res1 = $conn->query($sql1);

                    $row1 = $res2->fetch_assoc();
                    $row2 = $res1->fetch_assoc();
                    $Number_of_dependents = "";
                    $Marriage_Status = "";
                    $Educational_Status = "";
                    $Criminal_record = "";

                    if (!$res1) {
                      // Handle the SQL query error
                      echo "Error: " . $conn->error;
                    } else {
                      $found = $res2->num_rows;
                      if ($found > 0) {
                        $Number_of_dependents = $row1['Number_of_dependents'];
                        $Marriage_Status = $row1['Marriage_Status'];
                        $Educational_Status = $row1['Educational_Status'];
                        $Criminal_record = $row1['Criminal_record'];
                      } else {
                      }
                    }
                    ?>
                    <h5 class="mb-0">1.Personal Information</h5>
                    <!-- <small class="text-muted float-end"></small> -->
                  </div>
                  <div class="card-body">
                    <form id="personalForm" action="backend.php" method="POST">
                      <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                      <input type="hidden" name="add_user" value="1">
                      <input type="hidden" name="id" value='<?php echo $_SESSION['id'] ?>' />
                      <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="numberOfDependents">Number of
                          Dependents:<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="numberOfDependents2" class="input-group-text"><i class="bx bx-user"></i></span>
                            <input type="number" class="form-control" id="numberOfDependents" name="Number_of_dependents" value='<?php echo $Number_of_dependents ?>' placeholder="5" aria-label="John Doe" aria-describedby="numberOfDependents2" />
                          </div>
                        </div>

                        <label class="col-sm-2 col-form-label" for="marrigeStatus">Marriage
                          Status:<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="marrigeStatus2" class="input-group-text"><i class="bx bx-map-pin"></i></span>
                            <select value='<?php echo $Marriage_Status ?>' id="marrigeStatus" class="form-select" name="Marriage_Status">
                              <option value=""><?php echo $found ? $Marriage_Status : "Default Value" ?></option>
                              <option value="Married">Married</option>
                              <option value="Single">Single</option>
                              <option value="Divorced">Divorced</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="educationalStatus">Educational
                          Status:<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="educationalStatus2" class="input-group-text"><i class="bx bx-book-reader"></i></span>
                            <select value='<?php echo $Educational_Status ?>' id="educationalStatus" class="form-select" name="Educational_Status">
                              <option value=""><?php echo $found ? $Educational_Status : "Default Value" ?></option>
                              <option value="Below highSchool">Below highSchool </option>
                              <option value="Diploma">Diploma</option>
                              <option value="Degree">Degree</option>
                              <option value="Masters">Masters</option>
                              <option value="PHD">PHD</option>
                            </select>
                          </div>
                        </div>

                        <label class="col-sm-2 col-form-label" for="criminalRecord">Criminal
                          Records:<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="criminalRecord2" class="input-group-text"><i class="bx bx-book-reader"></i></span>
                            <select value='<?php echo $Criminal_record ?>' id="criminalRecord" class="form-select" name="Criminal_record">
                              <option value=""><?php echo $found ? $Criminal_record : "Default Value" ?></option>
                              <option value="No">No</option>
                              <option value="Yes/Past Five Years">Yes/Past Five Years</option>
                              <option value="Yes/More Than Five Years">Yes/More Than Five Years</option>
                            </select>
                          </div>
                        </div>

                      </div>
                      <?php

                      if (!$row2 || $row2['form_done'] == 0) {
                        // Display the link only if "form_done" is not set or is 0
                      ?>
                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                            <button id="submit-btn" type="submit" name='<?php echo $found ? "update_personal" : "add_personal" ?>' class="btn btn-primary">
                              <?php echo $found ? "Update" : "Submit" ?>
                            </button>
                          </div>
                        </div>
                      <?php
                      }
                      ?>
                    </form>
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
          <div class="loader" id="loader">
            <div class="loader-content">
              <div class="spinner"></div>
            </div>
          </div>
          <!-- / Content -->

          <!-- Footer -->
          <div class="container my-5">
            <?php

            include "../common/footer.php";
            ?>


            <!-- Page JS -->
            <script src="../assets/js/ui-toasts-personal.js"></script>

</body>

</html>
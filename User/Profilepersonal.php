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
            <h4 class="fw-bold py-3 mb-4">
              <span class="text-muted fw-light">Account Settings /</span> Personal Information
            </h4>
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
            <div class="row">
              <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                  <li class="nav-item">
                    <a class="nav-link" href="Profileuser.php"><i class="bx bx-user me-1"></i> Account</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-bell me-1"></i> Personal
                      Info</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="Profileeconomic.php"><i class="bx bx-link-alt me-1"></i> Economic Info</a>
                  </li>
                </ul>
                <?php
                $id = $_SESSION['id'];
                $sql2 = "SELECT * from personal where user_id='$id'";
                $res2 = $conn->query($sql2);
                $row2 = $res2->fetch_assoc();

                $sql1 = "SELECT * from users where user_id='$id'";
                $res = $conn->query($sql1);
                $row = $res->fetch_assoc();
                // Check if data exists, if not, set default values
                if (!$row2) {
                  $row2 = array(
                    'Number_of_dependents' => 'No data',
                    'Marriage_Status' => 'No data',
                    'Educational_Status' => 'No data',
                    'Criminal_record' => 'No data',
                    'user_id' => 'No Id'

                  );
                }
                ?>
                <div class="card">
                  <div class="card-body">
                    <form id="formAccountSettings" method="POST" action="backend.php" onsubmit="return false">
                      <div class="row">
                        <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                        <input type="hidden" name="update_personal" value="1">
                        <input type="hidden" name="id" value="<?php echo $row2['user_id']; ?>">
                        <div class="mb-3 col-md-6">
                          <label for="numberOfDependents" class="form-label">Number of Dependents</label>
                          <input required class="form-control" placeholder="5" type="text" id="numberOfDependents" name="numberOfDependents" value=" <?php echo $row2['Number_of_dependents']; ?>" readonly autofocus />
                        </div>
                        <input type="hidden" id="originalNumberOfDependents" placeholder="5" value="<?php echo $row2['Number_of_dependents']; ?>">
                        <div class="mb-3 col-md-6">
                          <label for="Marrige Status" class="form-label">Marrige Status</label>
                          <select required class="form-control" type="text" name="marrigeStatus" id="marrigeStatus" autofocus readonly>
                            <option value="" <?php if ($row2['Marriage_Status'] === 'No data')
                                                echo ' selected'; ?>>No data</option>
                            <option value="Married" <?php if ($row2['Marriage_Status'] === 'Married')
                                                      echo ' selected'; ?>>Married</option>
                            <option value="Single" <?php if ($row2['Marriage_Status'] === 'Single')
                                                      echo ' selected'; ?>>Single</option>
                            <option value="Divorced" <?php if ($row2['Marriage_Status'] === 'Divorced')
                                                        echo ' selected'; ?>>Divorced
                            </option>
                          </select>
                        </div>
                        <input type="hidden" id="originalMarrigeStatus" value="<?php echo $row2['Marriage_Status']; ?>">
                        <div class="mb-3 col-md-6">
                          <label for="educationalStatus" class="form-label">Educational Status</label>
                          <select required class="form-control" type="text" id="educationalStatus" name="educationalStatus" autofocus readonly>
                            <option value="" id="educationalStatus" <?php if ($row2['Educational_Status'] === 'No data')
                                                                      echo ' selected'; ?>>
                              No data
                            </option>

                            <option value="Below highSchool" id="educationalStatus" <?php if ($row2['Educational_Status'] === 'Below highSchool')
                                                                                      echo ' selected'; ?>>
                              Below highSchool
                            </option>
                            <option value="Diploma" <?php if ($row2['Educational_Status'] === 'Diploma')
                                                      echo ' selected'; ?>>
                              Diploma
                            </option>
                            <option value="Degree" <?php if ($row2['Educational_Status'] === 'Degree')
                                                      echo ' selected'; ?>>
                              Degree
                            </option>
                            <option value="Masters" <?php if ($row2['Educational_Status'] === 'Masters')
                                                      echo ' selected'; ?>>
                              Masters
                            </option>
                            <option value="PHD" <?php if ($row2['Educational_Status'] === 'PHD')
                                                  echo ' selected'; ?>>
                              PHD
                            </option>
                          </select>
                        </div>

                        <input type="hidden" id="originalEducationalStatus" value="<?php echo $row2['Educational_Status']; ?>">
                        <div class="mb-3 col-md-6">
                          <label for="criminalRecord" class="form-label">Criminal Record</label>
                          <select required class="form-control" type="text" name="criminalRecord" id="criminalRecord" autofocus readonly>
                            <option value="" <?php if ($row2['Criminal_record'] === 'No data')
                                                echo ' selected'; ?>>No data</option>
                            <option value="No" <?php if ($row2['Criminal_record'] === 'No')
                                                  echo ' selected'; ?>>No</option>
                            <option value="Yes/Past Five Years" <?php if ($row2['Criminal_record'] === 'Yes/Past Five Years')
                                                                  echo ' selected'; ?>>Yes/Past Five Years</option>
                            <option value="Yes/More Than Five Years" <?php if ($row2['Criminal_record'] === 'Yes/More Than Five Years')
                                                                        echo ' selected'; ?>>Yes/More Than Five Years
                            </option>
                          </select>
                        </div>
                        <input type="hidden" id="originalCriminalRecord" value="<?php echo $row2['Criminal_record']; ?>">
                        <?php
                        // session_start();

                        if (!$row || $row['form_done'] == 1) {
                          // Display the link only if "form_done" is not set or is 0
                        ?>
                          <div class="mt-2">
                            <!-- Change the button text -->
                            <button name="update_personal" type="submit" class="btn btn-primary me-2" id="updateButton">Update</button>
                            <button type="submit" id="cancelButton" class="btn btn-outline-secondary">Cancel</button>
                          </div>
                        <?php
                        }

                        ?>
                    </form>
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
          <div class="loader" id="loader">
            <div class="loader-content">
              <div class="spinner"></div>
            </div>
          </div>
          <!-- / Content -->

          <!-- Footer -->
        </div>
        <div class="container my-5">
          <?php

          include "../common/footer.php";
          ?>
          <script src="../assets/js/Updatefunctionlity.js"></script>
          <!--   <script src="../assets/js/populateuserlistprofilepersonal.js"></script> -->
</body>

</html>
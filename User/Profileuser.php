<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('user'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);

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
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">
              <span class="text-muted fw-light">Account Settings / </span> Account Information
            </h4>
            <!-- Content -->
            <div class="row">
              <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                  <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="Profilepersonal.php"><i class="bx bx-bell me-1"></i> Personal Info</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="Profileeconomic.php"><i class="bx bx-link-alt me-1"></i> Economic
                      Information</a>
                  </li>
                </ul>

                <?php
                $id = $_SESSION['id'];
                $sql1 = "SELECT * from users where user_id='$id'";
                $res = $conn->query($sql1);
                $row = $res->fetch_assoc();
                // Check if data exists, if not, set default values
                if (!$row) {
                  $row = array(
                    'name' => 'No data',
                    'TIN_Number' => 'No data',
                    'email' => 'No data',
                    'phone' => 'No data',
                    'dob' => 'No data',
                    'profile' => 'No data',
                    'createdOn' => 'No data',
                    'level' => 'No data'
                  );
                }
                ?>
                <div class="card mb-4">
                  <h5 class="card-header">Profile Details</h5>
                  <!-- Account -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12 col-sm-8 d-flex align-items-start align-items-sm-center gap-4">
                        <img src="<?php echo $row['profile']; ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                        <div class="button-wrapper">

                          <p class="text-muted mb-0"><small class="text-muted">Account Created on
                              <?php echo $row['createdOn'] ?>
                            </small></p>
                        </div>
                      </div>

                    </div>
                  </div>

                  <hr class="my-0" />

                  <div class="card-body">
                    <form id="formAccountSettings" method="POST" action="Profileuser.php" onsubmit="return false">
                      <div class="row">
                        <div class="mb-3 col-md-6">
                          <label for="fullName" class="form-label">Full Name</label>
                          <input class="form-control" autofocus disabled type="text" id="fullName" name="fullName" value="<?php echo $row['name'] ?>" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="tinNumber" class="form-label">TIN Number</label>
                          <input class="form-control" autofocus disabled type="text" name="tinNumber" id="tinNumber" value=" <?php echo $row['TIN_Number']; ?>" />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="email" class="form-label">E-mail</label>
                          <input class="form-control" autofocus disabled type="text" id="email" value="<?php echo $row['email']; ?>" name="email" value="john.doe@example.com" placeholder="john.doe@example.com" />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label class="form-label" for="phoneNumber">Phone Number</label>
                          <div class="input-group input-group-merge">
                            <span class="input-group-text">ET (+251)</span>
                            <input type="text" autofocus disabled id="phoneNumber" value="<?php echo $row['phone']; ?>" name="phoneNumber" class="form-control" placeholder="202 555 0111" />
                          </div>
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="dateOfBirth" class="form-label">Date of Birth</label>
                          <input class="form-control" type="date" id="dateOfBirth" name="dateOfBirth" value="<?php echo $row['dob']; ?>" autofocus disabled placeholder="01-12-2023" />
                        </div>

                      </div>
                    </form>
                  </div>
                  <!-- /Account -->
                </div>
              </div>
            </div>
          </div>
          <!-- / Content -->
          <div class="container my-5">
            <?php

            include "../common/footer.php";
            ?>
            <!-- Page JS -->
            <script src="../assets/js/pages-account-settings-account.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>

</html>
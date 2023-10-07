<?php
include "../connect.php";
session_start();
include "./AuthorizationAdmin.php";
include "../AdminCommons/head.php";
?>


<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <?php
      include "../AdminCommons/sidebar.php";
      ?>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <?php
        include "../AdminCommons/nav.php";
        ?>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span>General Information</h4>

            <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" id="error-toast">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto toast-title fw-semibold">Error</div>
                <small></small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body"></div>
            </div>

            <div class="loader" id="loader">
              <div class="loader-content">
                <div class="spinner"></div>
              </div>
            </div>

            <!-- Basic with Icons -->
            <div class="row">
              <div class="col-xxl">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"> General Information</h5>
                    <!-- <small class="text-muted float-end">Merged input group</small> -->
                  </div>
                  <div class="card-body">
                    <form id="userForm" action="backend.php" method="POST" enctype="multipart/form-data">
                      <div class="row mb-4">
                        <input type="hidden" name="add_user" value="1">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Name :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                            <input type="text" class="form-control" id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" name="name" />
                          </div>
                        </div>

                        <label class="col-sm-2 col-form-label" for="basic-icon-default-TIN">TIN /ID Number :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-TIN2" class="input-group-text"><i class="bx bx-credit-card-front"></i></span>
                            <input type="number" maxLength="10" minLength="10" class="form-control" id="basic-icon-default-TIN" placeholder="1234567890" aria-label="John Doe" aria-describedby="basic-icon-default-TIN2" name="TIN_Number" />
                          </div>
                        </div>
                      </div>
                      <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Email :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                            <input type="text" id="basic-icon-default-email" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-icon-default-email2" name="email" />
                          </div>
                          <!--<div class="form-text">You can use letters, numbers & periods</div> -->
                        </div>

                        <label class="col-sm-2 form-label" for="basic-icon-default-phone">Phone Number :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-phone"></i></span>
                            <input type="text" id="basic-icon-default-phone" class="form-control phone-mask" placeholder="658 799 8941" aria-label="658 799 8941" aria-describedby="basic-icon-default-phone2" name="phone" />
                          </div>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Image :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-image"></i></span>

                            <input id="basic-icon-default-photo" aria-describedby="basic-icon-default-photo2" class="form-control" type="file" id="formFile" name="profile" />
                          </div>
                        </div>
                        <label for="html5-datetime-local-input" class="col-md-2 col-form-label">Date of Birth:<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-dateOfBirth2" class="input-group-text"><i class="bx bx-calendar"></i></span>
                            <input class="form-control" type="date" value="2021-06-18" id="basic-icon-default-dateOfBirth" name="dob" />
                          </div>
                        </div>
                      </div>
                      <div class="row mb-4">
                        <!--            <label class="col-sm-2 col-form-label" for="basic-icon-default-company">Job Status :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-Job2" class="input-group-text"><i class="bx bx-buildings"></i></span>
                            <select id="basic-icon-default-Job" class="form-select" name="Job_Status">
                              <option value="">Default select</option>
                              <option value="Employed">Employed </option>
                              <option value="Unemployed">Unemployed</option>
                              <option value="Self Emplyed">Self Employed</option>
                            </select>
                          </div>
                        </div> -->

                      </div>
                      <div class="row justify-content-end">
                        <div class="col-sm-10">

                          <button id="submit-btn" type="submit" name="add_user" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- / Content -->

            <?php
            include "../AdminCommons/footer.php";
            ?>

            <!-- Page JS -->
            <script src="../assets/js/ui-toasts-user.js"></script>
            <!-- Place this tag in your head or just before your close body tag. -->
</body>

</html>
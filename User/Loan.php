<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
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

        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span>Personal Information</h4>

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
              <div class="col-xxl">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <?php
                    $sql = "SELECT * FROM personal WHERE user_id = '" . $_SESSION['id'] . "'";

                    $res = $conn->query($sql);
                    $found = $res->num_rows;
                    if ($res->num_rows) {
                      $row = $res->fetch_assoc();
                    }
                    ?>
                    <h5 class="mb-0">3.Credit Information</h5>
                    <!-- <small class="text-muted float-end">Merged input group</small> -->
                  </div>
                  <div class="card-body">
                    <form action="backend.php" method="POST">
                      <input type="hidden" name="id" value='<?php echo $_SESSION['id'] ?>' />
                      <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                      <div>
                        <?php
                        if (isset($_POST['totalprice'])) {
                          $_SESSION['price'] = $_POST['totalprice'];
                        }
                        echo   "Total price :" . $_SESSION['price'];
                        $sql = "SELECT * from users where id='$_SESSION[id]'";
                        $res = $conn->query($sql);
                        $row = $res->fetch_assoc();
                        echo "<br>Credit Limit:" . $row['credit_limit'];
                        $valid = $row['credit_limit'] >= $_SESSION['price'];
                        $found = $row['form_done'];

                        ?>
                        <input type="hidden" name="price" value='<?php echo $_SESSION['price'] ?>' />

                      </div>
                      <div class="row justify-content-end">
                        <div class="col-sm-10">
                          <?php if (!$found)
                            echo "<a " . (!$found ? '' : "type='submit' ") . "" . ($found ? '' : "href='./personal.php'") . ($found ? "name='checkout'" : "name='add_personal'") . " class='btn btn-primary text-white'>" . ($found ? "Complete checkout" : "Complete profile") . "</a>";
                          else {
                            if ($valid)
                              echo '<button type="submit" name="checkout" class="btn btn-primary">proceed to checkout</button>';
                            else
                              echo '<button type="button" class="btn btn-danger">Insufficent balance</button>';
                          }


                          ?>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- / Content -->
          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">
                ©
                <script>
                  document.write(new Date().getFullYear());
                </script>
                , made with ❤️ by
                <a href="https://ThemeSelection.com" target="_blank" class="footer-link fw-bolder">E-bidir</a>
              </div>
              <div>
                <a href="https://ThemeSelection.com/license/" class="footer-link me-4" target="_blank">License</a>
                <a href="https://ThemeSelection.com/" target="_blank" class="footer-link me-4">More
                  Themes</a>

                <a href="https://ThemeSelection.com/demo/ThemeSelection-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>

                <a href="https://github.com/ThemeSelection/ThemeSelection-html-admin-template-free/issues" target="_blank" class="footer-link me-4">Support</a>
              </div>
            </div>
          </footer>
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->


  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="../assets/vendor/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="../assets/js/ui-toasts-personal.js"></script>
  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
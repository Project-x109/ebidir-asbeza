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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

<style>
  @media (min-width: 1025px) {
    .h-custom {
      height: 100vh !important;
    }
  }

  /* Add CSS to make the card responsive */

  /* Media query for screens with a maximum width of 768px (typical mobile screens) */
  @media (max-width: 768px) {


    .card img {
      max-width: 65px;
      /* Adjust the maximum width of the image for mobile screens */
    }

    .card .ms-3 {
      font-size: 14px;
      /* Reduce font size for mobile screens */
    }

    .card .d-flex {
      flex-direction: column;
      /* Stack elements vertically for mobile screens */
      align-items: flex-start;
      /* Adjust alignment for mobile screens */

    }

    .card .d-flex .d-flex {
      justify-content: space-between;
      /* Adjust alignment for mobile screens */
      padding-top: 20px;
    }

    .card .d-flex .d-flex div {
      width: auto;
      /* Remove width constraints for mobile screens */
    }
  }
</style>

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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span>Payment Information</h4>

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
            <div class="col-xxl">
              <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">

                  <h5 class="mb-0">3.Payment Information</h5>
                  <!-- <small class="text-muted float-end"></small> -->
                </div>

              </div>
            </div>

            <div class="col-xxl">
              <div class="card">
                <div class="card-body p-4">
                  <div class="row">

                    <div class="col-lg-7">
                      <h5 class="mb-3"><a href="#!" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Continue shopping</a></h5>
                      <hr>

                      <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>

                          <p class="mb-0">You have 4 items in your cart</p>
                        </div>

                      </div>

                      <div class="card mb-3">
                        <div class="card-body">
                          <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                              <div>
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img1.webp" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px;">
                              </div>
                              <div class="ms-3">
                                <h5>Iphone 11 pro</h5>
                                <p class="small mb-0">256GB, Navy Blue</p>
                              </div>
                            </div>
                            <div class="d-flex flex-row align-items-center">
                              <div style="width: 50px;">
                                <h5 class="fw-normal mb-0">2</h5>
                              </div>
                              <div style="width: 80px;">
                                <h5 class="mb-0">ETB 900</h5>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card mb-3">
                        <div class="card-body">
                          <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                              <div>
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img2.webp" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px;">
                              </div>
                              <div class="ms-3">
                                <h5>Samsung galaxy Note 10 </h5>
                                <p class="small mb-0">256GB, Navy Blue</p>
                              </div>
                            </div>
                            <div class="d-flex flex-row align-items-center">
                              <div style="width: 50px;">
                                <h5 class="fw-normal mb-0">2</h5>
                              </div>
                              <div style="width: 80px;">
                                <h5 class="mb-0">ETB 900</h5>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card mb-3">
                        <div class="card-body">
                          <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                              <div>
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img3.webp" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px;">
                              </div>
                              <div class="ms-3">
                                <h5>Canon EOS M50</h5>
                                <p class="small mb-0">Onyx Black</p>
                              </div>
                            </div>
                            <div class="d-flex flex-row align-items-center">
                              <div style="width: 50px;">
                                <h5 class="fw-normal mb-0">1</h5>
                              </div>
                              <div style="width: 80px;">
                                <h5 class="mb-0">ETB 1199</h5>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card mb-3 mb-lg-0">
                        <div class="card-body">
                          <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                              <div>
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img4.webp" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px;">
                              </div>
                              <div class="ms-3">
                                <h5>MacBook Pro</h5>
                                <p class="small mb-0">1TB, Graphite</p>
                              </div>
                            </div>
                            <div class="d-flex flex-row align-items-center">
                              <div style="width: 50px;">
                                <h5 class="fw-normal mb-0">1</h5>
                              </div>
                              <div style="width: 80px;">
                                <h5 class="mb-0">ETB 1799</h5>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="col-lg-5">
                      <?php
                      $sql = "SELECT * FROM personal WHERE user_id = '" . $_SESSION['id'] . "'";
                      $res = $conn->query($sql);
                      $found = $res->num_rows;
                      if ($res->num_rows) {
                        $row = $res->fetch_assoc();
                      }
                      ?>


                      <div style="background-color: #cecece;" class="card text-white rounded-3">
                        <div class="card-body">
                          <div>
                            <?php
                            $_SESSION['price'] = 0; // Set an initial value
                            if (isset($_POST['totalprice'])) {
                              $_SESSION['price'] = $_POST['totalprice'];
                            }
                            $sql = "SELECT * from users where user_id='$_SESSION[id]'";
                            $res = $conn->query($sql);
                            $row = $res->fetch_assoc();
                            $valid = $row['credit_limit'] >= $_SESSION['price'];
                            $found = $row['form_done'];
                            $profileImage = $row['profile'];
                            $fullName = $row['name'];
                            $phone = $row['phone'];
                            $email = $row['email'];
                            $dateofbirth = $row['dob'];
                            $TIN_Number = $row['TIN_Number'];

                            ?>
                            <?php
                            $defaultAvatar = '../user/assets/img/avatars/Profile-Avatar-PNG.png'; // Set the path to your default avatar image

                            if (empty($profileImage)) {
                              $profileImage = $defaultAvatar;
                            }
                            ?>
                          </div>
                          <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Users details</h5>
                            <img src="<?php echo $profileImage ?>" class="img-fluid rounded-3" style="width: 45px;" alt="Avatar">
                          </div>
                          <form action="backend.php" method="POST">
                            <input type="hidden" name="id" value='<?php echo $_SESSION['id'] ?>' />
                            <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">

                            <div class="form-outline form-white mb-4">
                              <label class="form-label" for="typeName">User's Name</label>
                              <input type="text" id="typeName" disabled class="form-control form-control-lg" siez="17" placeholder="Cardholder's Name" value="<?php echo $fullName ?>" />

                            </div>

                            <div class="form-outline form-white mb-4">
                              <label class="form-label" for="typeName">Phone Number</label>
                              <input type="text" id="typeText" class="form-control form-control-lg" siez="17" placeholder="1234 5678 9012 3457" value="<?php echo $phone ?>" disabled minlength="19" maxlength="19" />
                            </div>

                            <div class="row mb-4">
                              <div class="col-md-6">
                                <div class="form-outline form-white">
                                  <label class="form-label" for="typeExp">Birth Date</label>
                                  <input type="text" id="typeExp" class="form-control form-control-lg" placeholder="MM/YYYY" size="7" id="exp" minlength="7" disabled value="<?php echo $dateofbirth ?>" maxlength="7" />

                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-outline form-white">
                                  <label class="form-label" for="typeText">Tin Number</label>
                                  <input disabled id="typeText" class="form-control form-control-lg" placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3" value="<?php echo $TIN_Number ?>" maxlength="3" />

                                </div>
                              </div>
                            </div>



                            <hr class="my-4">

                            <div class="d-flex justify-content-between">
                              <p class="mb-2 form-label">Current Credit Limit</p>
                              <p class="mb-2 form-label">
                                <?php echo $row['credit_limit'] . " ETB"; ?>
                              </p>
                            </div>

                            <div class="d-flex justify-content-between">
                              <p class="mb-2 form-label">Credit after purchase</p>
                              <p class="mb-2 form-label" form-label>5000 ETB</p>
                            </div>

                            <div class="d-flex justify-content-between mb-4">
                              <p class="mb-2 form-label">Total Price</p>
                              <p class="mb-2 form-label">
                                <?php echo $_SESSION['price'] . " ETB"; ?>
                              </p>
                            </div>

                            <div class="d-flex justify-content-between">

                              <?php if (!$found)
                                echo "<a " . (!$found ? '' : "type='submit' ") . "" . ($found ? '' : "href='./personal.php'") . ($found ? "name='checkout'" : "name='add_personal'") .
                                  " class='btn btn-dark text-white'>" . ($found ? "Complete checkout" : "Complete profile") . "</a>";
                              else {
                                if ($valid)
                                  echo '<button type="submit" name="checkout" class="btn btn-primary">Proceed to Checkout</button>';
                                else
                                  echo '<button type="button" class="btn btn-danger">Insufficent balance</button>';
                              }


                              ?>
                            </div>
                          </form>


                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- / Content -->
            <!-- Footer -->
          </div>
          <div class="container my-5">
            <?php
            include "../common/footer.php";
            ?>
</body>

</html>
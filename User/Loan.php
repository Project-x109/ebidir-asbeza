<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('user');
checkAuthorization($requiredRoles);
$_SESSION['token'] = bin2hex(random_bytes(35));
include "../common/head.php";
$total_price = 0;
$notify_url = '';
$return_url_failure = '';
$return_url_success = '';
$order_id = '';
if (isset($_SESSION['cart']) && count($_SESSION['cart']) === 0) {
  echo "<script>
      Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Cart Data is Empty'
      });
  </script>";
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@12"></script>
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
            <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive"
              aria-atomic="true" data-delay="2000">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto toast-title fw-semibold">Error</div>
                <small></small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body"></div>
            </div>
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
                      <h5 class="mb-3"><a href="#!" class="text-body"><i
                            class="fas fa-long-arrow-alt-left me-2"></i>Continue shopping</a></h5>
                      <hr>

                      <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>

                          <p class="mb-0">You have
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                              $count_of_item = count($_SESSION['cart']);
                            } else {
                              $count_of_item = 0;
                            }
                            echo $count_of_item ?>

                            items in your cart
                          </p>
                        </div>

                      </div>

                      <?php
                      if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                        $total_price = 0;
                        foreach ($_SESSION['cart'] as $product) {
                          if (isset($product['item_name'])) {
                            $item_name = isset($product['item_name']) ? $product['item_name'] : '';
                            $item_price = isset($product['item_price']) ? $product['item_price'] : '';
                            $item_image = isset($product['item_image']) ? $product['item_image'] : '';
                            $item_spec = isset($product['item_spec']) ? $product['item_spec'] : '';
                            $item_quantity = isset($product['item_quantity']) ? $product['item_quantity'] : '';
                            echo "
                                <div class='card mb-3'>
                                    <div class='card-body'>
                                        <div class='d-flex justify-content-between'>
                                            <div class='d-flex flex-row align-items-center'>
                                                <div>
                                                    <img src='" . $item_image . "' class='img-fluid rounded-3' alt='Shopping item' style='width: 65px;'>
                                                </div>
                                                <div class='ms-3'>
                                                    <h5>" . $item_name . "</h5>
                                                    <p class='small mb-0'>" . $item_spec . "</p>
                                                </div>
                                            </div>
                                            <div class='d-flex flex-row align-items-center'>
                                                <div style='width: 50px;'>
                                                    <h5 class='fw-normal mb-0'>" . $item_quantity . "</h5>
                                                </div>
                                                <div style='width: 80px;'>
                                                    <h5 class='mb-0'>" . $item_price . "</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                          } else {
                            $total_price = isset($product['total_price']) ? $product['total_price'] : '';
                            $order_id = isset($product['order_id']) ? $product['order_id'] : '';
                            $notify_url = isset($product['notify_url']) ? $product['notify_url'] : '';
                            $return_url_success = isset($product['return_url_success']) ? $product['return_url_success'] : '';
                            $return_url_failure = isset($product['return_url_failure']) ? $product['return_url_failure'] : '';
                          }
                        }
                      } else {
                        echo "<script>
                                  Swal.fire({
                                      icon: 'error',
                                      title: 'Error',
                                      text: 'Cart Data is Empty'
                                  });
                              </script>";
                      }
                      ?>
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
                            // Initialize variables
                            $_SESSION['price'] = 0;
                            $valid = false;
                            $found = false;
                            $profileImage = '';
                            $fullName = '';
                            $phone = '';
                            $email = '';
                            $dateofbirth = '';
                            $TIN_Number = '';
                            $credit_score_personal = 0;
                            $credit_score_economic = 0;

                            // Check if 'totalprice' is set in the POST data
                            if (isset($_POST['totalprice'])) {
                              $_SESSION['price'] = $_POST['totalprice'];
                            }

                            // Fetch user data
                            $user_id = $_SESSION['id'];
                            $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
                            $res = $conn->query($sql);

                            if ($res->num_rows > 0) {
                              $row = $res->fetch_assoc();
                              $valid = $row['credit_limit'] >= $total_price;
                              $found = $row['form_done'];
                              $profileImage = $row['profile'];
                              $fullName = $row['name'];
                              $phone = $row['phone'];
                              $email = $row['email'];
                              $dateofbirth = $row['dob'];
                              $TIN_Number = $row['TIN_Number'];

                              // Fetch personal data
                              $sqlpersonal = "SELECT * FROM personal WHERE user_id = '$user_id'";
                              $respersonal = $conn->query($sqlpersonal);

                              if ($respersonal->num_rows > 0) {
                                $rowpersonal = $respersonal->fetch_assoc();
                                $credit_score_personal = $rowpersonal['personal_score'];
                              }

                              // Fetch economic data
                              $sqleconomic = "SELECT * FROM economic WHERE user_id = '$user_id'";
                              $reseconomic = $conn->query($sqleconomic);

                              if ($reseconomic->num_rows > 0) {
                                $roweconomic = $reseconomic->fetch_assoc();
                                $credit_score_economic = $roweconomic['economic_score'];
                              }
                            }

                            $credit_score = $credit_score_personal + $credit_score_economic;
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
                            <img src="<?php echo $profileImage ?>" class="img-fluid rounded-3" style="width: 45px;"
                              alt="Avatar">
                          </div>
                          <form action="checkoutbackend.php" method="POST">
                            <input type="hidden" name="id" value='<?php echo $_SESSION['id'] ?>' />
                            <input type="hidden" name="order_id" value='<?php echo $order_id; ?>' />
                            <input type="hidden" name="return_url_success" value='<?php echo $return_url_success; ?>' />
                            <input type="hidden" name="return_url_failure" value='<?php echo $return_url_failure; ?>' />
                            <input type="hidden" name="notify_url" value='<?php echo $notify_url; ?>' />
                            <input type="hidden" name="token" id="csrf-token"
                              value="<?php echo $_SESSION['token'] ?? '' ?>">
                            <input type="hidden" name="totalprice" value="<?php echo $total_price; ?>">
                            <input type="hidden" name="credit_score" value="<?php echo $credit_score; ?>">
                            <div class="form-outline form-white mb-4">
                              <label class="form-label" for="typeName">User's Name</label>
                              <input type="text" id="typeName" disabled class="form-control form-control-lg" siez="17"
                                placeholder="Cardholder's Name" value="<?php echo $fullName ?>" />
                            </div>
                            <div class="form-outline form-white mb-4">
                              <label class="form-label" for="typeName">Phone Number</label>
                              <input type="text" id="typeText" class="form-control form-control-lg" siez="17"
                                placeholder="1234 5678 9012 3457" value="<?php echo $phone ?>" disabled minlength="19"
                                maxlength="19" />
                            </div>
                            <div class="row mb-4">
                              <div class="col-md-6">
                                <div class="form-outline form-white">
                                  <label class="form-label" for="typeExp">Birth Date</label>
                                  <input type="text" id="typeExp" class="form-control form-control-lg"
                                    placeholder="MM/YYYY" size="7" id="exp" minlength="7" disabled
                                    value="<?php echo $dateofbirth ?>" maxlength="7" />
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-outline form-white">
                                  <label class="form-label" for="typeText">Tin Number</label>
                                  <input disabled id="typeText" class="form-control form-control-lg"
                                    placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3"
                                    value="<?php echo $TIN_Number ?>" maxlength="3" />
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
                              <p class="mb-2 form-label">
                                <?php echo $row['credit_limit'] - $total_price . " ETB"; ?>
                              </p>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                              <p class="mb-2 form-label">Total Price</p>
                              <p class="mb-2 form-label">
                                <?php echo $total_price . " ETB"; ?>
                              </p>
                            </div>
                            <div class="d-flex justify-content-between">
                              <?php if (!$found)
                                echo "<a " . (!$found ? '' : "type='submit' ") . "" . ($found ? '' : "href='./personal.php'") . ($found ? "name='checkout'" : "name='add_personal'") .
                                  " class='btn btn-dark text-white'>" . ($found ? "Complete checkout" : "Complete profile") . "</a>";
                              else {
                                if (!isset($_SESSION['cart'])) {
                                  echo '<button type="submit" disabled name="checkout"  class="btn btn-primary">Proceed to Checkout</button>';
                                }
                                if ($valid && isset($_SESSION['cart']))
                                  echo '<button type="submit" name="checkout"  class="btn btn-primary">Proceed to Checkout</button>';
                                elseif (isset($_SESSION['cart']) && !$valid)
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
          </div>
          <div id="success-toast" class="bs-toast toast toast-placement-ex m-2 bg-primary top-0 end-0" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
            <div class="toast-header">
              <strong class="me-auto">Success</strong>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              <!-- Success message will be inserted here -->
            </div>
          </div>

          <div id="error-toast" class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
            <div class="toast-header">
              <strong class="me-auto">Error</strong>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              <!-- Error message will be inserted here -->
            </div>
          </div>
          <div class="container my-5">

            <?php
            include "../common/footer.php";
            ?>
            <script>
              // Check if the success message exists and display the success toast
              <?php if (isset($_SESSION['success'])): ?>
                Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: "<?php echo $_SESSION['success']; ?>",
                });
                <?php unset($_SESSION['success']); // Clear the success message 
                  ?>
              <?php endif; ?>

              // Check if the error message exists and display the error toast
              <?php if (isset($_SESSION['error'])): ?>
                document.addEventListener("DOMContentLoaded", function () {
                  var errorToast = new bootstrap.Toast(document.getElementById("error-toast"));
                  errorToast.show();
                  document.querySelector("#error-toast .toast-body").innerHTML = "<?php echo $_SESSION['error']; ?>";
                });
                <?php unset($_SESSION['error']); // Clear the error message 
                  ?>
              <?php endif; ?>
            </script>
</body>

</html>
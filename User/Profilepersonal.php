<?php
session_start();
include "../connect.php";

?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Account settings - Pages | ThemeSelection - Bootstrap 5 HTML Admin Template - Pro</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../assets/js/config.js"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="Dashbaord.php" class="app-brand-link">
            <span class="app-brand-logo demo">
              <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink">
                <defs>
                  <path
                    d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                    id="path-1"></path>
                  <path
                    d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                    id="path-3"></path>
                  <path
                    d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                    id="path-4"></path>
                  <path
                    d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                    id="path-5"></path>
                </defs>
                <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                    <g id="Icon" transform="translate(27.000000, 15.000000)">
                      <g id="Mask" transform="translate(0.000000, 8.000000)">
                        <mask id="mask-2" fill="white">
                          <use xlink:href="#path-1"></use>
                        </mask>
                        <use fill="#696cff" xlink:href="#path-1"></use>
                        <g id="Path-3" mask="url(#mask-2)">
                          <use fill="#696cff" xlink:href="#path-3"></use>
                          <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                        </g>
                        <g id="Path-4" mask="url(#mask-2)">
                          <use fill="#696cff" xlink:href="#path-4"></use>
                          <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                        </g>
                      </g>
                      <g id="Triangle"
                        transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                        <use fill="#696cff" xlink:href="#path-5"></use>
                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">e-bidir</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item">
            <a href="Dashbaord.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">User Dashboard</div>
            </a>
          </li>


          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pages</span>
          </li>
          <li class="menu-item active open">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-dock-top"></i>
              <div data-i18n="Account Settings">Account Settings</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="Profileuser.php" class="menu-link">
                  <div data-i18n="Account">Account Informtion</div>
                </a>
              </li>
              <li class="menu-item active open">
                <a href="Profilepersonal.php" class="menu-link">
                  <div data-i18n="Notifications">Personal Informtion</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="Profileeconomic.php" class="menu-link">
                  <div data-i18n="Connections">Economic Information</div>
                </a>
              </li>
            </ul>
          </li>
          

          <!-- Forms & Tables -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">User Pages</span></li>
          <!-- Forms -->
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-detail"></i>
              <div data-i18n="Form Layouts">Registration Forms</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="personal.php" class="menu-link">
                  <div data-i18n="Vertical Form">Personal Form</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="economic.php" class="menu-link">
                  <div data-i18n="Horizontal Form">Economic Form</div>
                </a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-detail"></i>
              <div data-i18n="Form Layouts">User Tables</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="credithistory.php" class="menu-link">
                  <div data-i18n="Vertical Form">Credit History</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="repaymenthistory.php" class="menu-link">
                  <div data-i18n="Horizontal Form">Repayment History</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="support.php" class="menu-link">
                  <div data-i18n="Horizontal Form">Support</div>
                </a>
              </li>
            </ul>
          </li>




        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                  aria-label="Search..." />
              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">

              <!-- Notification Button -->
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                  <i class="bx bx-bell bx-sm"></i>
                  <!-- Unread notification badge -->
                  <span class="badge bg-danger rounded-circle position-absolute  translate-middle">
                    3 <!-- Number of unread notifications -->
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <!-- Notification Item 1 -->
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-grow-1">
                          <span class="fw-semibold">Notification Title 1</span>
                          <p class="mb-0 text-muted">This is a sample notification.</p>
                          <small class="text-muted">2 hours ago</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- Notification Item 2 -->
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-grow-1">
                          <span class="fw-semibold">Notification Title 2</span>
                          <p class="mb-0 text-muted">Another sample notification.</p>
                          <small class="text-muted">3 hours ago</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- End of Notifications -->
                  <li class="dropdown-divider"></li>
                  <!-- View All Notifications -->
                  <li>
                    <a class="dropdown-item text-center" href="#">
                      View All Notifications
                    </a>
                  </li>
                </ul>
              </li>
              <!-- Place this tag where you want the button to render. -->
              <li class="nav-item lh-1 me-3 ms-4">
                <a class="github-button" href="https://github.com/ThemeSelection/ThemeSelection-html-admin-template-free"
                  data-icon="octicon-star" data-size="large" data-show-count="false"
                  aria-label="Star ThemeSelection/ThemeSelection-html-admin-template-free on GitHub">Remaining Credit
                  <span id="creditLimit">4</span>
                </a>
              </li>




              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">John Doe</span>
                          <small class="text-muted">Admin</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-user me-2"></i>
                      <span class="align-middle">My Profile</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-cog me-2"></i>
                      <span class="align-middle">Settings</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <span class="d-flex align-items-center align-middle">
                        <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                        <span class="flex-grow-1 align-middle">Billing</span>
                        <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                      </span>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="auth-login-basic.html">
                      <i class="bx bx-power-off me-2"></i>
                      <span class="align-middle">Log Out</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">
              <span class="text-muted fw-light">Account Settings /</span> Personal Information
            </h4>
            <!-- Toast with Placements -->
            <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive"
              aria-atomic="true" data-delay="2000">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto toast-title fw-semibold">Error</div>
                <small>11 mins ago</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body">Fruitcake chocolate bar tootsie roll gummies gummies jelly beans cake.</div>
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
                $sql2 = "SELECT * from personal where user_id=$id";
                $res2 = $conn->query($sql2);
                $row2 = $res2->fetch_assoc();
                ?>
                <?php
                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                  // Retrieve form data
                  $numberOfDependents = $_POST['numberOfDependents'];
                  $marrigeStatus = $_POST['marrigeStatus'];
                  $educationalStatus = $_POST['educationalStatus'];
                  $criminalRecord = $_POST['criminalRecord'];

                  // Update the database record
                  $id = $_SESSION['id'];
                  $sqlUpdate = "UPDATE personal SET
                  Number_of_dependents='$numberOfDependents',
                  Marriage_Status='$marrigeStatus',
                  Educational_Status='$educationalStatus',
                  Criminal_record='$criminalRecord'
                  WHERE user_id=$id";
                  if ($conn->query($sqlUpdate) === TRUE) {
                    // Record updated successfully
                    // You can redirect or show a success message here
                  } else {
                    // Error updating record
                    // You can handle errors here
                  }
                }
                ?>


                <div class="card">
                  <div class="card-body">
                    <form id="formAccountSettings" method="POST" onsubmit="return false">
                      <div class="row">
                        <div class="mb-3 col-md-6">
                          <label for="numberOfDependents" class="form-label">Number of Dependents</label>
                          <input class="form-control" type="text" id="numberOfDependents" name="numberOfDependents"
                            value=" <?php echo $row2['Number_of_dependents']; ?>" readonly autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="Marrige Status" class="form-label">Marrige Status</label>
                          <select class="form-control" type="text" name="marrigeStatus" id="marrigeStatus" autofocus
                            readonly>
                            <option value="">Default select</option>
                            <option <?php if ($row2['Marriage_Status'] === 'Married')
                              echo ' selected'; ?>>Married</option>
                            <option <?php if ($row2['Marriage_Status'] === 'Single')
                              echo ' selected'; ?>>Single</option>
                            <option <?php if ($row2['Marriage_Status'] === 'Divorced')
                              echo ' selected'; ?>>Divorced
                            </option>
                          </select>
                        </div>
                        <div class="mb-3 col-md-6">
                          <label for="educationalStatus" class="form-label">Educational Status</label>
                          <select class="form-control" type="text" id="educationalStatus" name="educationalStatus"
                            autofocus readonly>

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

                        <div class="mb-3 col-md-6">
                          <label for="criminalRecord" class="form-label">Criminal Record</label>
                          <input type="text" class="form-control" id="criminalRecord" name="criminalRecord"
                            value="<?php echo $row2['Criminal_record']; ?>" autofocus readonly />
                        </div>
                        <div class="mt-2">
                          <!-- Change the button text -->
                          <button type="submit" class="btn btn-primary me-2" id="updateButton">Update</button>
                          <button type="reset" class="btn btn-outline-secondary">Cancel</button>
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

  <!-- 
<div class="buy-ዝow">
    <a href="https://ThemeSelection.com/products/ThemeSelection-bootstrap-html-admin-template/" target="_blank"
      class="btn btn-danger btn-buy-now">Upgrade to Pro</a>
  </div>
-->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="../assets/vendor/js/menu.js"></script>
  <!-- endbuild -->
  <script src="../assets/js/common.js"></script>
  <!-- Vendors JS -->


  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>
  <script src="../assets/js/Updatefunctionlity.js"></script>
  <!--   <script src="../assets/js/populateuserlistprofilepersonal.js"></script> -->

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
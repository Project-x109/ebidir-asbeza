<?php
$id = $_SESSION['id'];
$sql1 = "SELECT * from users where user_id='$id'";
$res = $conn->query($sql1);
$row = $res->fetch_assoc();
?>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
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
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
            </div>
        </div>
        <!-- /Search -->


        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <?php
            // session_start();
            if ($_SESSION['role'] == 'user') {
            ?>
                <li class="nav-item lh-1 me-3 ms-4">
                    <a class="github-button" data-icon="octicon-star" data-size="large" data-show-count="false" aria-label="Star ThemeSelection/e-bidir-html-admin-template-free on GitHub">
                        <span id="creditLevel"><?php echo $_SESSION['level']; ?></span>
                    </a>
                </li>
            <?php
            }
            ?>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">

                    <?php
                    // session_start();
                    if ($_SESSION['role'] == 'branch' || $_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'delivery' || $_SESSION['role'] == 'EA') {
                    ?>
                        <div class="avatar avatar-online">
                            <img src="../assets/img/avatars/OIP.jfif" alt class="w-px-40 h-auto rounded-circle" />
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    // session_start();
                    if ($_SESSION['role'] == 'user') {
                    ?>
                        <div class="avatar avatar-online">
                            <img src="<?php echo $row['profile']; ?>" alt class="w-px-40 h-auto rounded-circle" />
                        </div>
                    <?php
                    }
                    ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <?php
                                // session_start();
                                if ($_SESSION['role'] == 'branch' || $_SESSION['role'] == 'Admin') {
                                ?>
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="../assets/img/avatars/OIP.jfif" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <?php
                                // session_start();
                                if ($_SESSION['role'] == 'user') {
                                ?>
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="<?php echo $row['profile']; ?>" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block"><?php
                                                                        $fullName = $row['name'];
                                                                        $parts = explode(' ', $fullName); // Split the full name by space
                                                                        $firstName = $parts[0]; // Get the first part (the first name)

                                                                        echo $firstName; // Display the first name
                                                                        ?></span>
                                    <small class="text-muted"><?php echo $row['role']; ?></small>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../changeanypassword.php">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Change Password</span>
                        </a>
                    </li>
                    <?php
                    // session_start();
                    if ($_SESSION['role'] == 'user') {
                        if (!$row || $row['form_done'] == 0) {
                            // Display the link only if "form_done" is not set or is 0
                    ?>
                            <li>
                                <a class="dropdown-item" href="../user/personal.php">
                                    <span class="d-flex align-items-center align-middle">
                                        <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                        <span class="flex-grow-1 align-middle">Add Personal Information</span>
                                    </span>
                                </a>
                            </li>
                    <?php
                        }
                    }
                    ?>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="confirmLogout()">
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
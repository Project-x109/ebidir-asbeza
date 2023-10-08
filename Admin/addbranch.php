<?php
include "../connect.php";
session_start();
include "../common/head.php";
include "./AuthorizationAdmin.php";

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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span>Branch Information
                        </h4>


                        <!-- Toast with Placements -->
                        <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                            <div class="toast-header">
                                <i class="bx bx-bell me-2"></i>
                                <div class="me-auto toast-title fw-semibold">Error</div>
                                <small></small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">Fruitcake chocolate bar tootsie roll gummies gummies jelly beans
                                cake.</div>
                        </div>
                        <!-- Toast with Placements -->
                        <!-- Basic with Icons -->
                        <div class="row">
                            <div class="col-xxl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0">1. Branch Information</h5>
                                        <small class="text-muted float-end">Merged input group</small>
                                    </div>
                                    <div class="card-body">
                                        <form id="branchForm" action="backend.php" method="POST">
                                            <div class="row mb-4">
                                                <input type="hidden" name="addbranch" value="1">
                                                <label class="col-sm-2 col-form-label" for="basic-icon-default-branchname">Branch Name :<span class="text-danger">*</span></label>
                                                <div class="col-sm-4">
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input name="branch_name" type="text" class="form-control" id="basic-icon-default-branchname" placeholder="John Doe" aria-label="John Doe" aria-describedby="basic-icon-default-branchname2" />
                                                    </div>
                                                </div>

                                                <label class="col-sm-2 form-label" for="basic-icon-default-phone">Phone
                                                    Number :<span class="text-danger">*</span></label>
                                                <div class="col-sm-4">
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-phone"></i></span>
                                                        <input name="phonenumber" type="text" id="basic-icon-default-phone" class="form-control phone-mask" placeholder="658 799 8941" aria-label="658 799 8941" aria-describedby="basic-icon-default-phone2" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Email :<span class="text-danger">*</span></label>
                                                <div class="col-sm-4">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                                        <input name="email" type="text" id="basic-icon-default-email" class="form-control" placeholder="amanuelgirma@gmail.com" aria-label="john.doe" aria-describedby="basic-icon-default-email2" />
                                                    </div>
                                                    <!--<div class="form-text">You can use letters, numbers & periods</div> -->
                                                </div>


                                                <label class="col-sm-2 col-form-label" for="basic-icon-default-location">Location :<span class="text-danger">*</span></label>
                                                <div class="col-sm-4">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                                                        <input name="location" type="text" id="basic-icon-default-location" class="form-control" placeholder="Bole" aria-label="Bole" aria-describedby="basic-icon-default-location2" />
                                                    </div>
                                                    <!--<div class="form-text">You can use letters, numbers & periods</div> -->
                                                </div>

                                            </div>
                                            <div class="row justify-content-end">
                                                <div class="col-sm-10">

                                                    <button id="submit-btn" type="submit" name="addbranch" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->
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
                    <?php
                    include "../AdminCommons/footer.php";
                    ?>
                    <!-- / Footer -->
                    <!-- Page JS -->
                    <script src="../assets/js/ui-toasts-branch.js"></script>


                    <!-- Place this tag in your head or just before your close body tag. -->
                    <script async defer src="https://buttons.github.io/buttons.js"></script>

</body>

</html>
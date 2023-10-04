<?php
session_start();
include "../common/head.php";// head part and all links
?>
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
<?php include '../common/sidebar.php';?> <!-- sidebar -->
            <div class="layout-page">
            <?php
include "../common/nav.php";  //<!-- sidebar -->
?>
            <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1  container-p-y">
                        <div class="row justify-content-center align-items-center mt-5">
                            <form class="form-card" id="cred itForm" action="backend.php" method="POST">
                                <p class="form-card-title">You can apply for credit here</p>
                                <p class="form-card-prompt">Insert the user's six-digit identification number</p>
                                <div class="form-card-input-wrapper">
                                    <input class="form-card-input" id="user" name="user" placeholder="______" maxlength="6" type="tel" id="identificationNumber">
                                    
                                    <div class="form-card-input-bg"></div>
                                </div>
                                <!-- <input type="text" name="data"/> -->
                                <div class="mt-5">
                                    <p class="call-again"><span class="underlined"></span></p>
                                    <button type='submit' name='dataholder' class="buttonapply">
                                        Apply
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" id="errorToast" style="display: none;">
                        <div class="toast-header">
                            <i class="bx bx-bell me-2"></i>
                            <div class="me-auto toast-title fw-semibold">Error</div>
                            <small></small>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                        </div>
                    </div>

 <?php
 include "../common/footer.php";
 ?>
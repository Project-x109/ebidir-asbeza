<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('branch','delivery','user'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
?>
<?php
include "../common/head.php";
?>
<style>
    .container1 {
        max-width: 380px;
        margin: 50px auto;
        overflow: hidden;
    }

    .printer-top {
        z-index: 1;
        border: 6px solid #666666;
        height: 6px;
        border-bottom: 0;
        border-radius: 6px 6px 0 0;
        background: #333333;
    }

    .printer-bottom {
        z-index: 0;
        border: 6px solid #666666;
        height: 6px;
        border-top: 0;
        border-radius: 0 0 6px 6px;
        background: #333333;
    }

    .paper-container {
        position: relative;
        overflow: hidden;
        height: 467px;
    }

    .paper {
        background: #ffffff;
        font-family: 'Poppins', sans-serif;
        height: 447px;
        position: absolute;
        z-index: 2;
        margin: 0 12px;
        margin-top: -12px;
        animation: print 5000ms cubic-bezier(0.68, -0.55, 0.265, 0.9) infinite;
        -moz-animation: print 5000ms cubic-bezier(0.68, -0.55, 0.265, 0.9) infinite;
    }

    .main-contents {
        margin: 0 12px;
        padding: 24px;
    }


    .jagged-edge {
        position: relative;
        height: 20px;
        width: 100%;
        margin-top: -1px;
    }

    .jagged-edge:after {
        content: "";
        display: block;
        position: absolute;
        /* //bottom: 20px; */
        left: 0;
        right: 0;
        height: 20px;
        background: linear-gradient(45deg,
                transparent 33.333%,
                #ffffff 33.333%,
                #ffffff 66.667%,
                transparent 66.667%),
            linear-gradient(-45deg,
                transparent 33.333%,
                #ffffff 33.333%,
                #ffffff 66.667%,
                transparent 66.667%);
        background-size: 16px 40px;
        background-position: 0 -20px;
    }

    .success-icon {
        text-align: center;
        font-size: 48px;
        height: 72px;
        background: #359d00;
        border-radius: 50%;
        width: 72px;
        height: 72px;
        margin: 16px auto;
        color: #fff;
    }

    .success-title {
        font-size: 22px;
        font-family: 'Poppins', sans-serif;
        text-align: center;
        color: #666;
        font-weight: bold;
        margin-bottom: 16px;
    }

    .success-description {
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        line-height: 21px;
        color: #999;
        text-align: center;
        margin-bottom: 24px;
    }

    .order-details {
        text-align: center;
        color: #333;
        font-weight: bold;

    }

    .order-number-label {
        font-size: 18px;
        margin-bottom: 8px;
    }

    .order-number {
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        line-height: 48px;
        font-size: 48px;
        padding: 8px 0;
        margin-bottom: 24px;
    }

    .complement {
        font-size: 18px;
        margin-bottom: 8px;
        color: #32a852;
    }
</style>

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
                    <div class="container-xxl flex-grow-1  container-p-y">
                        <div class="row justify-content-center align-items-center mt-5">
                            <div class="container1">
                                <div class="printer-top"></div>

                                <div class="paper-container">
                                    <div class="printer-bottom"></div>

                                    <div class="paper">
                                        <div class="main-contents">
                                            <div class="success-icon">&#10004;</div>
                                            <div class="success-title">
                                                Payment Complete
                                            </div>
                                            <div class="success-description">
                                                Thank you for completing the payment! You will shortly receive an email of your payment.
                                            </div>
                                            <div class="order-details">
                                                <div class="order-number-label">Transaction ID</div>
                                                <div class="order-number"><?= $_SESSION['user_id'] ?></div>
                                                <div class="complement">Thank You!</div>
                                            </div>
                                        </div>
                                        <div class="jagged-edge"></div>
                                    </div>
                                </div>
                            </div>


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
                    <div class="container my-5">
                    <!-- / Content -->
                    <?php
                    include "../common/footer.php";
                    ?>
</body>

</html>
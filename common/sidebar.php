<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="Dashbaord.php" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="100.000000pt" height="100.000000pt" viewBox="0 0 213 316" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg">
                    <path fillRule="evenodd" clipRule="evenodd" d="M74.0566 43.497L49.4793 56.6282C34.0104 85.7695 22.3393 118.033 19.9095 145.264H31.1515C31.1515 130.306 43.277 118.181 58.2346 118.181C73.1921 118.181 85.3176 130.306 85.3176 145.264H190.46C190.223 148.292 190.046 155.438 189.929 160.186L189.928 160.209C189.897 161.449 189.871 162.525 189.848 163.319H0.0593382C0.231512 157.272 0.659133 151.253 1.33831 145.264C5.36395 109.765 18.2273 75.3335 39.1178 42.5265C41.6431 38.5606 44.2858 34.6185 47.0443 30.7011C47.704 29.7666 48.3686 28.8342 49.0368 27.9038H66.6309H82.7397C84.0858 25.6579 88.6055 18.4951 90.0261 16.2641C92.5514 12.2982 95.1941 8.35612 97.9526 4.43872C98.6123 3.50427 99.2769 2.57181 99.9451 1.6414H117.539H212.405C188.872 34.6995 158.327 100.946 158.327 132.889H137.022C138.477 104.569 159.099 57.9986 175.973 27.9038L163.284 17.2347H123.07L100.969 27.9038H161.497C138.482 60.2344 122.249 96.0218 119.337 130.491C119.209 131.29 119.086 132.089 118.966 132.889H97.6615C97.7906 132.089 97.9239 131.29 98.0614 130.491C102.2 106.45 110.162 82.7487 122.019 59.911C122.589 58.8147 123.167 57.7205 123.754 56.6282L111.964 43.497H74.0566ZM21.0366 195.074C24.5265 227.538 34.764 259.776 51.5758 290.527H148.223C129.624 259.484 124.959 235.781 120.288 202.713H141.505C141.505 228.154 156.215 268.503 174.678 299.555C177.798 304.803 181.113 310.003 184.622 315.148H165.306H46.9494C16.6762 270.754 0.836592 222.32 0 173.988H188.726V192.864H20.8095C20.8817 193.601 20.9574 194.338 21.0366 195.074ZM99.2692 311.865C104.255 311.865 108.297 307.824 108.297 302.838C108.297 297.852 104.255 293.81 99.2692 293.81C94.2834 293.81 90.2415 297.852 90.2415 302.838C90.2415 307.824 94.2834 311.865 99.2692 311.865Z" style="fill: #2d3a5e;" />
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

        <?php
        // session_start();
        if ($_SESSION['role'] == 'branch' || $_SESSION['role']=="delivery") {
        ?>
            <li class="menu-item">
                <a href="Dashbaord.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                    <div data-i18n="Analytics">Dashboard</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="applyforme.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-merge"></i>
                    <div data-i18n="Analytics">Apply For Me</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="branchrepayments.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-detail"></i>
                    <div data-i18n="Analytics">Repayments</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="userlistbranch.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-table"></i>
                    <div data-i18n="Analytics">User Lists</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="creditapplicationbranch.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-table"></i>
                    <div data-i18n="Analytics">Credit Applications</div>
                </a>
            </li>
         

        <?php
        }
        ?>
       <?php
if($_SESSION['role']=='delivery')
{
?>
   <li class="menu-item">
                <a href="creditapplicationOnline.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-table"></i>
                    <div data-i18n="Analytics">Online Applications</div>
                </a>
</li>
<?php
}
?>
        <?php
        if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'EA') {
        ?>
            <li class="menu-item">
                <a href="Dashbaord.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                    <div data-i18n="Analytics">Dashboard</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="addbranch.php" class="menu-link">
                    <i class="menu-icon tf-icons bi bi-ui-checks-grid"></i>
                    <div data-i18n="Analytics">Branch Registarion</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="addusers.php" class="menu-link">

                    <i class="menu-icon tf-icons bi bi-ui-checks"></i>
                    <div data-i18n="Analytics">User Registarion</div>
                </a>
            </li>



            <li class="menu-item">
                <a href="branches.php" class="menu-link">
                    <i class=" menu-icon tf-iconsbi bi-card-checklist"></i>

                    <div data-i18n="Analytics">Branch Lists</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="userslist.php" class="menu-link">

                    <i class="menu-icon tf-icons bi bi-people-fill"></i>
                    <div data-i18n="Analytics">User Lists</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="transactions.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-table"></i>
                    <div data-i18n="Analytics">Repayments</div>
                </a>
            </li>


            <li class="menu-item">
                <a href="creditapplication.php" class="menu-link">

                    <i class=" menu-icon tf-icons bi bi-credit-card-2-front-fill"></i>
                    <div data-i18n="Analytics">Credit Applications</div>
                </a>
            </li>
        <?php
        } ?>
        <?php
        if ($_SESSION['role'] == 'user') {
        ?>

            <li class="menu-item">
                <a href="Dashbaord.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                    <div data-i18n="Analytics">Dashboard</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="Profileuser.php" class="menu-link">
                    <i class='menu-icon tf-icons bx bxs-user-account'></i>
                    <div data-i18n="Account">Account Informtion</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="Profilepersonal.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                    <div data-i18n="Notifications">Personal Informtion</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="Profileeconomic.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-wallet"></i>
                    <div data-i18n="Connections">Economic Information</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="credithistory.php" class="menu-link">
                    <i class='menu-icon tf-icons bx bxs-credit-card'></i>
                    <div data-i18n="Vertical Form">Credit History</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="repaymenthistory.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div data-i18n="Horizontal Form">Repayment History</div>
                </a>
            </li>

        <?php
        } ?>
    </ul>
</aside>
<?php
session_start();
include "../connect.php";
?>

<?php
include "../UsersCommon/head.php";
?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php
            include "../UsersCommon/sidebar.php";
            ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php
                include "../UsersCommon/nav.php";
                ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1  container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User/</span>Repayment History</h4>

                        <div class="row">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">Credit History</h5>
                                    <div class="table-responsive text-nowrap ms-3 me-3">
                                        <table class="table table-striped" id="table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Transaction Id</th>
                                                    <th>Loan ID</th>
                                                    <th>Loan Amount</th>
                                                    <th>Credit Limit</th>
                                                    <th>Credit Level</th>
                                                    <th>Credit Score</th>
                                                    <th>Date Paid</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $x = 1;
                                                $sql = "SELECT *,loans.credit_score as score FROM `transactions` inner join loans on loans.id=transactions.loan_id where transactions.user_id='" . $_SESSION['id'] . "'";

                                                $res = $conn->query($sql);
                                                if ($res->num_rows > 0)
                                                    while ($row = $res->fetch_assoc()) {
                                                        echo "<tr>
                                                    <td>" . ($x++) . "</td>
                                                    <td>" . $row['transaction_id'] . "</td>
                                                    <td>" . $row['loan_id'] . "</td>
                                                    <td>" . $row['loan_amount'] . "</td> 
                                                    <td>" . $row['credit_limit'] . "</td>   
                                                    <td>" . $row['credit_level'] . "</td>   
                                                    <td>" . $row['score'] . "</td>
                                                    <td>" . $row['updatedOn'] . "</td>";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--/ Striped Rows -->
                        </div>
                    </div>
                    <!-- / Content -->

                    <?php
                    include "../UsersCommon/footer.php";
                    ?>
                    <script src="../assets/js/jquery-3.7.0.js"></script>
                    <script src="../assets/js/jquery.dataTables.min.js"></script>

                    <script>
                        new DataTable('#table-striped');
                    </script>

</body>

</html>
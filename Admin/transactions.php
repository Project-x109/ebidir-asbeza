<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
?>
<?php
include "../common/head.php";

?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <!-- Layout container -->

            <?php
            include "../common/sidebar.php";

            ?>
            <div class="layout-page">
                <!-- Navbar -->
                <?php
                include "../common/nav.php";
                ?>

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1  container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User/</span>Repayments</h4>

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
                                                    <th>User Name</th>
                                                    <th>User ID</th>
                                                    <th>Transaction ID</th>
                                                    <th>Loan Amount</th>
                                                    <th>Credit Limit</th>
                                                    <th>Credit Level</th>
                                                    <th>Date Paid</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT *  FROM `transactions` inner join users on users.user_id=transactions.user_id";
                                                $res = $conn->query($sql);
                                                $x = 1;
                                                if ($res->num_rows > 0)
                                                    while ($row = $res->fetch_assoc()) {
                                                        $disabled = $row['status'] == 'paid' ? "disabled" : "";
                                                        echo "<tr>
                                                        <td>" . ($x++) . "</td>
                                                        <td>" . $row['name'] . "</td>
                                                        <td>" . $row['user_id'] . "</td>
                                                        <td>" . $row['transaction_id'] . "</td>
                                                        <td>" . $row['loan_amount'] . "</td> 
                                                        <td>" . $row['credit_limit'] . "</td>   
                                                        <td>" . $row['credit_level'] . "</td>   
                                                        <td>" . $row['updatedOn'] . "</td>   
                                                        </tr>";
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
                    <div class="container my-5">
                        <?php
                        include "../common/footer.php"

                        ?>
                        <script src="../assets/js/jquery-3.7.0.js"></script>
                        <script src="../assets/js/jquery.dataTables.min.js"></script>

                        <script>
                            new DataTable('#table-striped');
                        </script>

                        <!-- Page JS -->
</body>

</html>
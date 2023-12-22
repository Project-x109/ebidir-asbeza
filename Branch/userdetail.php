<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('branch','delivery'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
include "../common/head.php";
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

                    <div class="container-xxl flex-grow-1  container-p-y">


                        <div class="row ">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">User credit History</h5>
                                    <div class="table-responsive text-nowrap  ms-3 me-3">
                                        <table class="table table-striped" id="table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Loanid</th>
                                                    <th>Loan Amount</th>
                                                    <th>Credit Limit</th>
                                                    <th>Credit Limit Level</th>
                                                    <th>Status</th>
                                                    <th>Updated On</th>
                                                </tr>
                                            </thead>

                                            <tbody class="table-border-bottom-0">

                                                <?php
                                                if (isset($_GET['user_id'])) {
                                                    $user_id = $_GET['user_id'];

                                                    // Fetch user information
                                                    $user_sql = "SELECT * FROM users WHERE user_id = '$user_id'";
                                                    $user_result = $conn->query($user_sql);
                                                    $user = $user_result->fetch_assoc();

                                                    // Fetch user's loans and transactions
                                                    $loan_transaction_sql = "SELECT 
                                                            l.id AS loan_id,
                                                            l.price AS loan_amount,
                                                            l.credit_score AS credit_limit,
                                                            l.status,
                                                            CASE
                                                                WHEN l.status = 'paid' THEN t.transaction_id
                                                               
                                                                ELSE 'Pending'
                                                            END AS transaction_id,
                                                            CASE
                                                                WHEN l.status = 'paid' THEN t.loan_id
                                                                WHEN l.status = 'pending' THEN l.id
                                                                ELSE NULL
                                                            END AS transaction_loan_id,
                                                            CASE
                                                                WHEN l.status = 'paid' THEN t.loan_amount
                                                                WHEN l.status = 'pending' THEN l.price
                                                                ELSE NULL
                                                            END AS transaction_loan_amount,
                                                            CASE
                                                                WHEN l.status = 'paid' THEN 
                                                                    CASE
                                                                        WHEN t.credit_limit IS NOT NULL THEN t.credit_limit
                                                                        ELSE 'Pending'
                                                                    END
                                                                WHEN l.status = 'pending' THEN 'Pending'
                                                                ELSE NULL
                                                            END AS transaction_credit_limit,
                                                            CASE
                                                                WHEN l.status = 'paid' THEN t.credit_level
                                                                ELSE 'Pending'
                                                            END AS transaction_credit_level,
                                                            CASE
                                                                WHEN l.status = 'paid' THEN t.updatedOn
                                                                ELSE 'Pending'
                                                            END AS transaction_updatedOn
                                                        FROM loans AS l
                                                        LEFT JOIN transactions AS t ON l.id = t.loan_id
                                                        LEFT JOIN branch AS b ON t.updatedBy = b.branch_id
                                                        LEFT JOIN users AS u ON l.user_id = u.user_id
                                                        WHERE l.user_id = '$user_id' AND (l.provider = '" . $_SESSION['id'] . "')
                                                        ORDER BY l.id DESC";

                                                    $loan_transaction_result = $conn->query($loan_transaction_sql);
                                                ?>
                                                    <div class="container">
                                                        <!-- h2>User Detail</h2> -->
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="userName">Name:</label>
                                                                    <p id="userName"><?php echo $user['name']; ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="userID">User ID:</label>
                                                                    <p id="userID"><?php echo $user['user_id']; ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="userID">Email:</label>
                                                                    <p id="userID"><?php echo $user['email']; ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="userID">Phone Number:</label>
                                                                    <p id="userID"><?php echo $user['phone']; ?></p>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Add other user information fields as needed -->
                                                        </div>
                                                    </div>


                                                <?php
                                                    // Display user's transactions and loans
                                                    if ($loan_transaction_result->num_rows > 0) {

                                                        while ($row = $loan_transaction_result->fetch_assoc()) {
                                                            echo '<tr>';

                                                            echo '<td>' . $row['transaction_id'] . '</td>';
                                                            echo '<td>' . $row['transaction_loan_id'] . '</td>';
                                                            echo '<td>' . $row['transaction_loan_amount'] . '</td>';
                                                            echo '<td>' . $row['transaction_credit_limit'] . '</td>';
                                                            echo '<td>' . $row['transaction_credit_level'] . '</td>';
                                                            echo '<td>' . $row['status'] . '</td>';
                                                            echo '<td>' . $row['transaction_updatedOn'] . '</td>';
                                                            echo '</tr>';
                                                        }
                                                        echo '</tbody>';
                                                        echo '</table>';
                                                    } else {
                                                        echo 'No loans found for this user.';
                                                    }
                                                } else {
                                                    echo 'User ID not provided.';
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



                </div>
                <!-- / Content -->

                <div class="container my-5">
                    <?php
                    include "../common/footer.php";
                    ?>

                    <script>
                        $(document).ready(function() {
                            $('#table-striped').DataTable();
                        });
                    </script>
                    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
                    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>





</body>

</html>
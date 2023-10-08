<?php
include "../connect.php";
session_start();
include "./AuthorizationBranch.php";
?>
<?php
include "../BranchCommon/head.php"
?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php
            include "../common/sidebar.php";
            ?>
            <div class="layout-page">
                <?php
                include "../common/nav.php";

                ?>
                <!-- Navbar -->

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1  container-p-y">

                        <div class="row">
                            <!-- Striped Rows -->
                            <div class="col-md-6 col-lg-12 col-xl-12 order-0 mb-4">
                                <div class="card">
                                    <h5 class="card-header">Lists of Loans</h5>
                                    <div class="table-responsive text-nowrap ms-3 me-3">
                                        <table id="table-striped" class="display" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>User ID</th>
                                                    <th class="sortable" data-column="userName">User Name</th>
                                                    <th>Loan Amount</th>
                                                    <th>Credit Limit</th>
                                                    <th>Credit Score</th>
                                                    <th>Requsted Date</th>
                                                    <th>Provider</th>
                                                    <th>Update payment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT *, loans.user_id AS userID, loans.status AS status, loans.id AS loan_id FROM loans INNER JOIN users ON users.user_id = loans.user_id WHERE loans.status != 'paid' AND loans.provider = '$_SESSION[id]'";
                                                $res = $conn->query($sql);
                                                if ($res->num_rows > 0)
                                                    while ($row = $res->fetch_assoc()) {
                                                        $disabled = $row['status'] == 'paid' ? "disabled" : "";
                                                        echo "<tr>
                                                            <td>" . $row['userID'] . "</td>
                                                            <td>" . $row['name'] . "</td>
                                                            <td>" . $row['price'] . "</td>
                                                            <td>" . $row['credit_limit'] . "</td> 
                                                            <td>" . $row['credit_score'] . "</td>   
                                                            <td>" . $row['createdOn'] . "</td>   
                                                            <td>" . $row['provider'] . "</td>   
                                                            <td><button class='btn btn-success' $disabled value='$row[loan_id]' onclick='update(this)'>update</button></td>   
                                                            </tr>";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Modal Structure (empty modal) -->
                                    <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalToggleLabel">Loan status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card">
                                                        <div class="card-body" id="modalContent">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Update Modal -->

                                    <div class="modal fade" id="statusModal" aria-labelledby="statusModalLabel" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="loanID" />
                                                    <div id="statusRadioContainer">
                                                        <div class="radio-buttons-container">
                                                            <div class="radio-button">
                                                                <input name="radio-group" id="radio2" class="radio-button__input" type="radio">
                                                                <label for="radio2" class="radio-button__label">
                                                                    <span class="radio-button__custom"></span>

                                                                    pending
                                                                </label>
                                                            </div>
                                                            <div class="radio-button">
                                                                <input name="radio-group" id="radio1" class="radio-button__input" type="radio">
                                                                <label for="radio1" class="radio-button__label">
                                                                    <span class="radio-button__custom"></span>

                                                                    decline
                                                                </label>
                                                            </div>
                                                            <div class="radio-button">
                                                                <input name="radio-group" id="radio3" class="radio-button__input" type="radio">
                                                                <label for="radio3" class="radio-button__label">
                                                                    <span class="radio-button__custom"></span>

                                                                    Closed
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary" onclick="updateStatus()">Save Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--/ Striped Rows -->
                    </div>



                    <!-- Modal Structure (empty modal) -->
                    <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalToggleLabel">Loan Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <div class="card-body" id="modalContent">
                                            <!-- Modal content will be dynamically generated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Update Modal -->
                    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Status update radio buttons will be dynamically generated here -->
                                    <div id="statusRadioContainer">
                                        <!-- Radio buttons will be generated here dynamically using JavaScript -->
                                    </div>
                                </div>
                                <input type="hidden" id="loanID">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="saveStatus()">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content -->
                <?php
                include "../BranchCommon/footer.php"
                ?>
                <!--  <script>
                    function update(e) {
                        let x = e.value;
                        let xhr = new XMLHttpRequest();
                        xhr.onload = function() {
                            // alert(this.responseText)
                            document.location = '';
                        }
                        xhr.open("GET", "ajax.php?loan_id=" + x)
                        xhr.send();
                    }
                </script> -->
                <script>
                    function update(e) {
                        let x = e.value;
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'You are about to update this record.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, update it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If the user confirms, proceed with the update
                                let xhr = new XMLHttpRequest();
                                xhr.onload = function() {
                                    document.location = '';
                                }
                                xhr.open("GET", "ajax.php?loan_id=" + x)
                                xhr.send();
                            }
                        })
                    }
                </script>
                <script src="../assets/js/jquery-3.7.0.js"></script>
                <script src="../assets/js/jquery.dataTables.min.js"></script>

                <script>
                    new DataTable('#table-striped');
                </script>
</body>

</html>
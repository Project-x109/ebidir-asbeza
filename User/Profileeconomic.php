<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
$requiredRoles = array('user'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
$_SESSION['token'] = bin2hex(random_bytes(35));

?>

<?php
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
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">Account Settings / </span> Economic Information
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

                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                                    <li class="nav-item">
                                        <a class="nav-link" href="Profileuser.php"><i class="bx bx-user me-1"></i>
                                            Account</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="Profilepersonal.php"><i class="bx bx-bell me-1"></i>
                                            Personal Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-link-alt me-1"></i> Economic Info</a>
                                    </li>
                                </ul>
                                <?php
                                $id = $_SESSION['id'];
                                $sql3 = "SELECT * from economic where user_id='$id'";
                                $res3 = $conn->query($sql3);
                                $row3 = $res3->fetch_assoc();
                                $sql1 = "SELECT * from users where user_id='$id'";
                                $res = $conn->query($sql1);
                                $row = $res->fetch_assoc();
                                // Check if data exists, if not, set default values
                                if (!$row3) {
                                    $row3 = array(
                                        'field_of_employeement' => 'No data',
                                        'number_of_income' => 'No data',
                                        'year' => 'No data',
                                        'position' => 'No data',
                                        'user_id' => 'No Id',
                                        'salary' => 'No data'
                                    );
                                }
                                ?>
                                <?php
                                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                                    // Retrieve form data
                                    $fieldOfEmployment = $_POST['fieldOfEmployment'];
                                   /*  $branch = $_POST['branch']; */
                                    $yearOfEmployment = $_POST['yearOfEmployment'];
                                    $numberOfIncome = $_POST['numberOfIncome'];
                                    $position = $_POST['position'];

                                    // Update the database record
                                    $id = $_SESSION['id'];
                                    $sqlUpdate = "UPDATE economic SET
                                    field_of_employeement='$fieldOfEmployment',
                                    number_of_income='$numberOfIncome',
                                    year='$yearOfEmployment',
                                   /*  branch='$branch', */
                                    position='$position'
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
                                        <form id="formAccountSettings" action="backend.php" method="POST" onsubmit="return false">
                                            <div class="row">
                                                <input type="hidden" name="token" id="csrf-token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                                                <input type="hidden" name="update_economic" value="1">
                                              <!--   <input type="hidden" name="id" value='<?php echo $_SESSION['id'] ?>' /> -->
                                                <div class="mb-3 col-md-6">
                                                    <label for="fieldOfEmployment" class="form-label">Field of
                                                        Employement</label>
                                                    <input class="form-control" type="text" id="fieldOfEmployment" name="field_of_employeement" value=" <?php echo $row3['field_of_employeement']; ?>" autofocus readonly />
                                                </div>
                                                <input type="hidden" id="originalfieldOfEmployment" value="<?php echo $row3['field_of_employeement']; ?>">
                                                <div class="mb-3 col-md-6">
                                                    <label for="numberOfIncome" class="form-label">Number of
                                                        Income</label>
                                                    <input class="form-control" type="text" name="number_of_income" id="numberOfIncome" value="<?php echo $row3['number_of_income']; ?>" autofocus readonly />
                                                </div>
                                                <input type="hidden" id="originalnumberOfIncome" value="<?php echo $row3['number_of_income']; ?>">

                                                <div class="mb-3 col-md-6">
                                                    <label for="yearOfEmployment" class="form-label">Year of
                                                        Employment</label>
                                                    <input class="form-control date-picker" type="text" id="yearOfEmployment" name="year" value="<?php echo $row3['year']; ?>" placeholder="Select a date" autofocus readonly data-toggle="flatpickr" data-enable-time="false">
                                                </div>
                                                <input type="hidden" id="originalyearOfEmployment" value="<?php echo $row3['year']; ?>">

                                                <!-- <div class="mb-3 col-md-6">
                                                    <label for="branch" class="form-label">Branch Name</label>
                                                    <select type="text" class="form-control" id="branch" name="branch" autofocus readonly>
                                                        <?php
                                                        // Query to fetch branch names from the "branch" table
                                                        $branchQuery = "SELECT branch_name FROM branch";
                                                        $branchResult = $conn->query($branchQuery);

                                                        // Check if there are rows in the result
                                                        if ($branchResult && $branchResult->num_rows > 0) {
                                                            while ($branchRow = $branchResult->fetch_assoc()) {
                                                                $branchName = $branchRow['branch_name'];
                                                                // Use the branch name to generate an <option> element
                                                                echo "<option value=\"$branchName\"";
                                                                // Check if the branch name matches the value in $row3['branch'] and mark it as selected
                                                                if ($row3['branch'] === $branchName) {
                                                                    echo ' selected';
                                                                }
                                                                echo ">$branchName</option>";
                                                            }
                                                        } else {
                                                            // If there are no branch names in the database, you can display a default option
                                                            echo '<option value="No data" selected>No data</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div> -->
                                                <!-- <input type="hidden" id="originalbranch" value="<?php echo $row3['branch']; ?>"> -->
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="position">Position</label>
                                                    <div class="input-group input-group-merge">
                                                        <input type="text" id="position" value="<?php echo $row3['position']; ?>" name="position" class="form-control" placeholder="CEO" autofocus readonly />
                                                    </div>
                                                </div>
                                                <input type="hidden" id="originalposition" value="<?php echo $row3['position']; ?>">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="salary">Salary</label>
                                                    <div class="input-group input-group-merge">

                                                        <input type="number" id="basic-icon-default-salary" class="form-control" placeholder="56790" aria-label="salary" aria-describedby="basic-icon-default-salary" autofocus readonly name="salary" value="<?php echo $row3['salary']; ?>" />
                                                    </div>
                                                </div>
                                                <input type="hidden" id="originalsalary" value="<?php echo $row3['salary']; ?>">

                                            </div>
                                            <?php
                                            if (!$row || $row['form_done'] == 1) {
                                                // Display the link only if "form_done" is not set or is 0
                                            ?>
                                                <div class="mt-2">
                                                    <button type="submit" id="updateButton" name="update_economic" class="btn btn-primary me-2">Update</button>
                                                    <button type="submit" id="cancelButton" class="btn btn-outline-secondary">Cancel</button>
                                                </div>
                                            <?php
                                            }

                                            ?>
                                        </form>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="loader" id="loader">
                        <div class="loader-content">
                            <div class="spinner"></div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <div class="container my-5">
                        <?php
                        include "../common/footer.php";
                        ?>
                        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

                        <!-- Page JS -->
                        <script src="../assets/js/Updatefunctionlityeconomic.js"></script>
</body>

</html>
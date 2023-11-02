<?php
include "../connect.php";
session_start();
include "../common/Authorization.php";
include "../common/head.php";
$requiredRoles = array('Admin', 'EA'); // Define the required roles for the specific page
checkAuthorization($requiredRoles);
$_SESSION['token'] = bin2hex(random_bytes(35));
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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span>General Information</h4>

            <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" id="error-toast">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto toast-title fw-semibold">Error</div>
                <small></small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body"></div>
            </div>

            <div class="loader" id="loader">
              <div class="loader-content">
                <div class="spinner"></div>
              </div>
            </div>

            <!-- Basic with Icons -->
            <div class="row">
              <div class="col-xxl">
                <div class="card mb-4">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"> General Information</h5>
                    <!-- <small class="text-muted float-end"></small> -->
                  </div>
                  <div class="card-body">
                    <form id="userForm" method="POST" enctype="multipart/form-data">
                      <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">

                      <div class="row mb-4">
                        <input type="hidden" name="add_user" value="1">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Full Name :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                            <input type="text" class="form-control" id="basic-icon-default-fullname" placeholder="John Doe" aria-label="Amanuel Girma Mekonnen" aria-describedby="basic-icon-default-fullname2" name="name" />
                          </div>
                        </div>

                        <label class="col-sm-2 col-form-label" for="basic-icon-default-TIN">TIN /ID Number :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-TIN2" class="input-group-text"><i class="bx bx-credit-card-front"></i></span>
                            <input type="number" maxLength="10" minLength="10" class="form-control" id="basic-icon-default-TIN" placeholder="1234567890" aria-label="John Doe" aria-describedby="basic-icon-default-TIN2" name="TIN_Number" />
                          </div>
                        </div>
                      </div>
                      <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Email :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                            <input type="text" id="basic-icon-default-email" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-icon-default-email2" name="email" />
                          </div>
                          <div class="form-text">You can use letters, numbers & periods</div>
                        </div>

                        <label class="col-sm-2 form-label" for="basic-icon-default-phone">Phone Number :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-phone"></i></span>
                            <input type="text" id="basic-icon-default-phone" class="form-control phone-mask" placeholder="0919485189" aria-label="658 799 8941" aria-describedby="basic-icon-default-phone2" name="phone" />
                          </div>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Image :<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-image"></i></span>
                            <input type="hidden" name="croppedImageData" id="croppedImageData" value="">
                            <input id="basic-icon-default-photo" aria-describedby="basic-icon-default-photo2" class="form-control" type="file" id="formFile" name="profile" />
                          </div>
                        </div>
                        <label for="html5-datetime-local-input" class="col-md-2 col-form-label">Date of Birth:<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                          <div class="input-group input-group-merge">
                            <span id="basic-icon-default-dateOfBirth2" class="input-group-text"><i class="bx bx-calendar"></i></span>
                            <input class="form-control" type="date" value="2021-06-18" id="basic-icon-default-dateOfBirth" name="dob" />
                          </div>
                        </div>
                      </div>
                      <div class="row mb-4">

                      </div>
                      <div class="row justify-content-end">
                        <div class="col-sm-10">

                          <button id="submit-btn" type="submit" name="add_user" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </form>




                    <div class="row">
                      <div class="col-md-4">&nbsp;</div>
                      <div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="cropModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Crop Image Before Upload</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="img-container">
                                <div class="row">
                                  <div class="col-md-8">
                                    <img src="" id="sample_image" />
                                  </div>
                                  <div class="col-md-4">
                                    <div class="preview" id="image-preview"></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button id="crop-button" class="btn btn-primary">Crop Image</button>
                              <button type="button" class="btn btn-secondary" data-dismiss="cropModal">Cancel</button>
                            </div>
                          </div>
                        </div>
                      </div>






                    </div>
                  </div>
                </div>
              </div>
              <!-- / Content -->
            </div>
            <div class="container my-5">
              <?php
              include "../common/footer.php";
              ?>

              <!-- Page JS -->
              <!-- <script src="../assets/js/ui-toasts-user.js"></script> -->

              <script>
                var croppedImageData = null;

                function showLoader() {
                  $('#loader').fadeIn();
                }

                // Hide the loader when the response is received
                function hideLoader() {
                  $('#loader').fadeOut();
                }

                $(document).ready(function() {
                  var $modal = $('#cropModal');
                  var image = document.getElementById('sample_image');
                  var cropper;

                  $('#basic-icon-default-photo').change(function(event) {
                    var files = event.target.files;
                    if (files && files.length > 0) {
                      var reader = new FileReader();
                      reader.onload = function(event) {
                        image.src = reader.result;
                        $modal.modal('show');
                      };
                      reader.readAsDataURL(files[0]);
                    }
                  });

                  $modal
                    .on('shown.bs.modal', function() {
                      cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 3,
                        preview: '.preview'
                      });
                    })
                    .on('hidden.bs.modal', function() {
                      cropper.destroy();
                      cropper = null;
                    });

                  $('#crop-button').click(function() {
                    canvas = cropper.getCroppedCanvas({
                      width: 400,
                      height: 400
                    });

                    canvas.toBlob(function(blob) {
                      var reader = new FileReader();
                      reader.readAsDataURL(blob);
                      reader.onloadend = function() {
                        croppedImageData = reader.result;
                        $('#sample_image').attr('src', croppedImageData);
                        $modal.modal('hide');
                      };
                    });
                  });

                  $('#userForm').on('submit', function(event) {
                    event.preventDefault(); // Prevent the default form submission behavior
                    showLoader();
                    // Perform form validation here
                    if (!validateForm()) {
                      return; // Stop further processing if validation fails
                    }

                    if (croppedImageData) {
                      $('#croppedImageData').val(croppedImageData);
                    }
                    // Serialize the form data
                    var formData = new FormData(this);
                    $.ajax({
                      url: 'backend.php', // URL to send the form data
                      method: 'POST',
                      data: formData,
                      contentType: false,
                      processData: false,
                      error: function(jqXHR, textStatus, errorThrown) {
                        hideLoader();
                        $('#error-toast .toast-body').text('Backend Error: ' + errorThrown);
                        showErrorMessage();
                      },
                      success: function(response) {
                        console.log(response);
                        hideLoader(); // Hide the loader on success
                        // Check if the response contains validation errors
                        if (response.errors) {
                          var errorContainer = $('#error-toast .toast-body');
                          errorContainer.empty(); // Clear any previous errors
                          console.log('AJAX request initiated');
                          // Loop through the validation errors and display them in the toast
                          $.each(response.errors, function(key, value) {
                            errorContainer.append('<p>' + value + '</p>');
                          });
                          console.log('AJAX request initiated');

                          // Display the error toast for frontend validation errors
                          showErrorMessage();
                        } else {
                          // If no errors, you can redirect or show a success message as needed
                          if (response.success) {
                            Swal.fire({
                              icon: 'success',
                              title: 'Success',
                              text: response.success
                            }).then(result => {
                              // You can add additional actions after the user clicks "OK"
                              if (result.isConfirmed) {
                                // Clear and reset the form fields
                                $('#userForm')[0].reset();

                                // Create a new FormData object with the cleared form data
                                var formData = new FormData($('#userForm')[0]);

                                // Re-enable the submit button after a delay (e.g., 2 seconds)
                                setTimeout(function() {
                                  $('#submit-btn').prop('disabled', false);
                                  $('#submit-btn').text('Submit');
                                }, 2000);
                              }
                            });
                          }
                          console.log('AJAX request initiated');
                        }
                      }
                    });
                  });

                  function validateForm() {
                    var isValid = true;

                    const fields = [{
                        id: 'basic-icon-default-fullname',
                        error: 'Name is required.'
                      },
                      {
                        id: 'basic-icon-default-TIN',
                        error: 'TIN Number is required.'
                      },
                      {
                        id: 'basic-icon-default-dateOfBirth',
                        error: 'Date of Birth is required.'
                      },
                      {
                        id: 'basic-icon-default-email',
                        error: 'Email is required.'
                      },
                      {
                        id: 'basic-icon-default-phone',
                        error: 'Phone is required.'
                      },
                      {
                        id: 'basic-icon-default-photo',
                        error: 'Image is required.'
                      }
                    ];

                    const numberRegex = /^[0-9]+$/;
                    const nameRegex = /^[A-Za-z\s]+$/;
                    const validPhoneRegex = /(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(\+\s*2\s*5\s*1\s*9\s*((([0-9]\s*){8}\s*)))|(0\s*9\s*(([0-9]\s*){8}))|(0\s*7\s*(([0-9]\s*){8}))/;
                    const validEmailRegex = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
                    $('#error-toast .toast-body').empty();
                    const photoInput = document.getElementById('basic-icon-default-photo');
                    const photoFile = photoInput.files[0];
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

                    for (const field of fields) {
                      const input = document.getElementById(field.id);
                      const value = input.value.trim();

                      if (value === '') {
                        isValid = false;
                        $('#error-toast .toast-body').append('<p>' + field.error + '</p>');
                        showErrorMessage();
                        break;
                      }
                      if (field.id === 'basic-icon-default-TIN') {
                        if (!numberRegex.test(value) || value.length !== 10) {
                          isValid = false;
                          $('#error-toast .toast-body').append('<p>TIN Number must contain numbers and have a length of ten (0-9).</p>');
                          showErrorMessage();
                          break;
                        }
                      }
                      if (field.id === 'basic-icon-default-fullname') {
                        if (!nameRegex.test(value)) {
                          isValid = false;
                          $('#error-toast .toast-body').append('<p>Name can only contain alphabets and spaces.</p>');
                          showErrorMessage();
                          break;
                        }
                      }

                      if (field.id === 'basic-icon-default-phone') {
                        if (!validPhoneRegex.test(value)) {
                          isValid = false;
                          $('#error-toast .toast-body').append('<p>Invalid Phone Number.</p>');
                          showErrorMessage();
                          break;
                        }
                      }

                      if (field.id === 'basic-icon-default-email') {
                        if (!validEmailRegex.test(value)) {
                          isValid = false;
                          $('#error-toast .toast-body').append('<p>Invalid Email Address.</p>');
                          showErrorMessage();
                          break;
                        }
                      }

                      if (field.id === 'basic-icon-default-dateOfBirth') {
                        const dobDate = new Date(value);
                        const todayDate = new Date();
                        const eighteenYearsAgo = new Date(todayDate.getFullYear() - 18, todayDate.getMonth(), todayDate.getDate());

                        if (isNaN(dobDate) || dobDate > eighteenYearsAgo) {
                          isValid = false;
                          $('#error-toast .toast-body').append('<p>Date of Birth must indicate you are at least 18 years old.</p>');
                          showErrorMessage();
                          break;
                        }
                      }

                      if (!photoFile) {
                        isValid = false;
                        $('#error-toast .toast-body').append('<p>Image is required.</p>');
                        showErrorMessage();
                      } else {
                        const maxSize = 1024 * 1024; // 1 MB in bytes
                        if (photoFile.size > maxSize) {
                          isValid = false;
                          $('#error-toast .toast-body').append('<p>Image size should be below 1 MB.</p>');
                          showErrorMessage();
                        }

                        if (!allowedTypes.includes(photoFile.type)) {
                          isValid = false;
                          $('#error-toast .toast-body').append('<p>Allowed image types are JPG, JPEG, and PNG.</p>');
                          showErrorMessage();
                        }
                      }
                    }

                    return isValid;
                  }
                  // Function to display the error toast for frontend validation errors
                  function showErrorMessage() {
                    var toastPlacement = new bootstrap.Toast($('#error-toast'));
                    toastPlacement.show();
                    hideLoader();
                  }
                });
              </script>
              <!-- Place this tag in your head or just before your close body tag. -->
</body>

</html>
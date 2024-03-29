function showLoader() {
  $("#loader").fadeIn();
}

// Hide the loader when the response is received
function hideLoader() {
  $("#loader").fadeOut();
}
$(document).ready(function () {
  $("#economicForm").on("submit", function (event) {
    event.preventDefault();
    showLoader();
    var csrfToken = document.getElementById('csrf-token').getAttribute('value');
    // Perform form validation here
    if (!validateForm()) {
      return; // Stop further processing if validation fails
    }

    // Serialize the form data
    var formData = new FormData(this);

    $.ajax({
      url: "backend.php", // Change the URL to the appropriate endpoint
      method: "POST",
      data: formData,
      headers: {
        'X-CSRF-Token': csrfToken // Send the CSRF token as a header
      },
      contentType: false,
      processData: false,
      error: function (jqXHR, textStatus, errorThrown) {
        // Display a backend error message in the error toast
        $("#error-toast .toast-body").text("Backend Error: " + errorThrown);
        showErrorMessage();
      },
      success: function (response) {
        hideLoader();
        if (response.errors) {
          var errorContainer = $("#error-toast .toast-body");
          errorContainer.empty();
          $.each(response.errors, function (key, value) {
            errorContainer.append("<p>" + value + "</p>");
          });
          showErrorMessage();
        } else {
          if (response.success) {
            // Clear and reset the form
            $("#economicForm")[0].reset();
            Swal.fire({
              icon: "success",
              title: "Success",
              text: response.success,
            }).then((result) => {
              if (result.isConfirmed) {
          
                window.location.href = "loan.php";
              }
            });
          }
        }
      },
    });
  });

  function validateForm() {
    // Add your form validation logic here
    var isValid = true;

    const fields = [
      {
        id: "basic-icon-default-fieldofEmployment",
        error: "field of Employment is required.",
      },
      {
        id: "basic-icon-default-numberofincome",
        error: "Number of Income is required.",
      },
      {
        id: "html5-datetime-local-input-YearofEmployment",
        error: "Year of Employment is required.",
      },
     
      {
        id: "basic-icon-default-position",
        error: "Position is required.",
      },
      {
        id: "basic-icon-default-salary",
        error: "Salary is required.",
      },
    ];
    const numberRegex = /^[0-9]+$/;
    // Clear previous toast error messages
    $("#error-toast .toast-body").empty();

    // Iterate through the fields and check their values
    for (const field of fields) {
      const input = document.getElementById(field.id);
      const value = input.value.trim();

      if (value === "") {
        isValid = false;
        // Display an error message in the toast
        $("#error-toast .toast-body").append("<p>" + field.error + "</p>");
        showErrorMessage();
        break; // Stop further validation on the first empty field
      }

      if (field.id === "basic-icon-default-numberofincome") {
        // Check if TIN Number field contains only numbers
        if (!numberRegex.test(value)) {
          isValid = false;
          // Display an error message in the toast
          $("#error-toast .toast-body").append(
            "<p>Number of Income should be Number only</p>"
          );
          showErrorMessage();
          break; // Stop further validation if TIN Number is invalid
        }
      }

      if (field.id === "basic-icon-default-salary") {
        // Check if TIN Number field contains only numbers
        if (!numberRegex.test(value)) {
          isValid = false;
          // Display an error message in the toast
          $("#error-toast .toast-body").append(
            "<p>Salary should be Number only</p>"
          );
          showErrorMessage();
          break; // Stop further validation if TIN Number is invalid
        }
      }
    }
    return isValid;
  }

  function showErrorMessage() {
    var toastPlacement = new bootstrap.Toast($("#error-toast"));
    toastPlacement.show();
    hideLoader();
  }
});

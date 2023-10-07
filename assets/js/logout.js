function confirmLogout() {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to log out.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, log out'
    }).then((result) => {
        if (result.isConfirmed) {
            // If the user confirms, log out
            window.location.href = '../logout.php';
        }
    })
}
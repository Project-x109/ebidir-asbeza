// Get the badge element
const badge = document.querySelector('.badge.bg-danger');

// Add a click event listener to the badge
badge.addEventListener('click', function(event) {
    // Prevent the default behavior of clicking a link
    event.preventDefault();

    // Remove the red background color from the badge
    badge.classList.remove('bg-danger');
});

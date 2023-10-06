// Get the current page's URL
var currentPageUrl = window.location.href;

// Find all menu items
var menuItems = document.querySelectorAll('.menu-item');

// Loop through each menu item
menuItems.forEach(function (menuItem) {
  // Get the link within the menu item
  var link = menuItem.querySelector('.menu-link');

  // Get the href attribute of the link
  var href = link.getAttribute('href');

  // Check if the current page URL contains the href of the link
  if (currentPageUrl.includes(href)) {
    // Add the "active open" class to the matching menu item
    menuItem.classList.add('active', 'open');
  } else {
    // Remove the "active open" class from other menu items
    menuItem.classList.remove('active', 'open');
  }
});

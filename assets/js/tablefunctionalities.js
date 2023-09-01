document.addEventListener('DOMContentLoaded', function () {
  const table = document.getElementById('table-striped');
  const tbody = table.querySelector('tbody');
  const recordsPerPageSelect = document.getElementById('recordsPerPage');
  const prevPageLink = document.getElementById('prevPage');
  const nextPageLink = document.getElementById('nextPage');
  const tableSearch = document.getElementById('tableSearch');

  let currentPage = 1;
  let recordsPerPage = parseInt(recordsPerPageSelect.value);

  // Function to update the table based on current page and records per page
  function updateTable() {
    const rows = tbody.querySelectorAll('tr');
    const startIndex = (currentPage - 1) * recordsPerPage;
    const endIndex = startIndex + recordsPerPage;

    rows.forEach((row, index) => {
      if (index >= startIndex && index < endIndex) {
        row.style.display = 'table-row';
      } else {
        row.style.display = 'none';
      }
    });

    prevPageLink.disabled = currentPage === 1;
    nextPageLink.disabled = endIndex >= rows.length || (rows.length === 0 && currentPage === 1);
  }

  // Handle records per page change
  recordsPerPageSelect.addEventListener('change', function () {
    recordsPerPage = parseInt(this.value);
    currentPage = 1;
    updateTable();
  });

  // Handle previous page click
  prevPageLink.addEventListener('click', function (e) {
    e.preventDefault();
    if (currentPage > 1) {
      currentPage--;
      updateTable();
    }
  });

  // Handle next page click
  nextPageLink.addEventListener('click', function (e) {
    e.preventDefault();
    const rows = tbody.querySelectorAll('tr');
    const startIndex = currentPage * recordsPerPage;
    if (startIndex < rows.length) {
      currentPage++;
      updateTable();
    }
  });

  // Handle table search
  tableSearch.addEventListener('input', function () {
    const searchTerm = tableSearch.value.trim().toLowerCase();
    const rows = tbody.querySelectorAll('tr');
    rows.forEach(row => {
      const rowData = Array.from(row.querySelectorAll('td:not(.actions)'))
        .map(cell => cell.textContent.trim().toLowerCase())
        .join('');
      if (rowData.includes(searchTerm)) {
        row.style.display = 'table-row';
      } else {
        row.style.display = 'none';
      }
    });
  });

  // Initialize the table
  updateTable();
});

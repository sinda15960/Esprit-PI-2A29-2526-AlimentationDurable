document.addEventListener('DOMContentLoaded', function() {
    initSearch();
});

function initSearch() {
    const searchInput = document.querySelector('.search-box input');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            searchTable(this.value);
        });
    }
}

function searchTable(searchTerm) {
    const tables = document.querySelectorAll('.data-table');
    tables.forEach(table => {
        const rows = table.getElementsByTagName('tr');
        const term = searchTerm.toUpperCase();
        
        for(let i = 1; i < rows.length; i++) {
            let found = false;
            const cells = rows[i].getElementsByTagName('td');
            for(let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if(cell && cell.textContent.toUpperCase().indexOf(term) > -1) {
                    found = true;
                    break;
                }
            }
            rows[i].style.display = found ? '' : 'none';
        }
    });
}
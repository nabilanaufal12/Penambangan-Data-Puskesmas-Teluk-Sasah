/**
 * PRABOTIK - Client Interactive Scripts
 * UPTD Puskesmas Teluk Sasah, Kabupaten Bintan
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Tab Switching Functionality
    const tabButtons = document.querySelectorAll('.prabotik-tab-btn');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const parent = this.closest('.tab-wrapper') || document;
            
            // Deactivate sibling buttons
            this.parentElement.querySelectorAll('.prabotik-tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Deactivate sibling panes
            parent.querySelectorAll('.tab-pane-prabotik').forEach(pane => {
                pane.style.display = 'none';
            });

            // Activate target pane
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.style.display = 'block';
            }
        });
    });

    // 2. Client-Side Table Filter / Search
    const searchInputs = document.querySelectorAll('[data-table-search]');
    searchInputs.forEach(input => {
        input.addEventListener('input', function () {
            const tableId = this.getAttribute('data-table-search');
            const table = document.getElementById(tableId);
            if (!table) return;

            const query = this.value.toLowerCase().trim();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});

/**
 * Utility: Export HTML Table data to CSV file
 */
function exportTableToCSV(tableId, filename = 'prabotik_data.csv') {
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        // Skip hidden rows
        if (row.style.display === 'none') return;
        
        let cols = [];
        row.querySelectorAll('th, td').forEach(cell => {
            let text = cell.innerText.replace(/"/g, '""').trim();
            cols.push('"' + text + '"');
        });
        if (cols.length > 0) {
            csv.push(cols.join(','));
        }
    });

    const csvContent = 'data:text/csv;charset=utf-8,\uFEFF' + encodeURIComponent(csv.join('\n'));
    const link = document.createElement('a');
    link.setAttribute('href', csvContent);
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}


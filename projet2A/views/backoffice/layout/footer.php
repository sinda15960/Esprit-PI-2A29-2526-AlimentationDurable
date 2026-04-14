<?php
// Fermeture des éléments ouverts dans le header
?>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/backoffice.js"></script>
    
    <script>
        // Auto-hide alerts
        document.querySelectorAll('.alert-dismissible').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    if(alert.parentElement) alert.remove();
                }, 300);
            }, 5000);
        });
        
        // Table row selection with Ctrl/Cmd key
        let lastSelectedRow = null;
        document.querySelectorAll('.data-table tbody tr').forEach(row => {
            row.addEventListener('click', function(e) {
                // Ne pas sélectionner si on clique sur un bouton d'action
                if(e.target.closest('.actions') || e.target.closest('.btn-action')) {
                    return;
                }
                
                const checkbox = this.querySelector('.recipe-select');
                if(checkbox) {
                    // Ctrl+Click pour sélection multiple
                    if(e.ctrlKey || e.metaKey) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    } 
                    // Shift+Click pour sélectionner une plage
                    else if(e.shiftKey && lastSelectedRow) {
                        const rows = Array.from(document.querySelectorAll('.data-table tbody tr'));
                        const start = rows.indexOf(lastSelectedRow);
                        const end = rows.indexOf(this);
                        const [min, max] = [Math.min(start, end), Math.max(start, end)];
                        
                        for(let i = min; i <= max; i++) {
                            const cb = rows[i].querySelector('.recipe-select');
                            if(cb) {
                                cb.checked = true;
                                cb.dispatchEvent(new Event('change'));
                            }
                        }
                    }
                    else {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                    
                    lastSelectedRow = this;
                }
            });
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + S pour sauvegarder
            if(e.ctrlKey && e.key === 's') {
                e.preventDefault();
                const submitBtn = document.querySelector('.btn-submit');
                if(submitBtn) submitBtn.click();
            }
            
            // Ctrl + N pour nouvelle recette
            if(e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                const createBtn = document.querySelector('a[href*="backCreateRecipe"]');
                if(createBtn) window.location.href = createBtn.href;
            }
            
            // Delete key pour supprimer
            if(e.key === 'Delete' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                const selectedCheckboxes = document.querySelectorAll('.recipe-select:checked');
                if(selectedCheckboxes.length > 0) {
                    e.preventDefault();
                    if(confirm(`Supprimer ${selectedCheckboxes.length} recette(s) ?`)) {
                        document.getElementById('bulkDeleteForm')?.submit();
                    }
                }
            }
            
            // Escape pour annuler
            if(e.key === 'Escape') {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    modal.style.display = 'none';
                });
            }
        });
        
        // Confirmation before dangerous actions
        window.confirmAction = function(message, callback) {
            if(confirm(message)) {
                callback();
            }
        };
        
        // Export functions
        window.exportToCSV = function(data, filename) {
            const csv = data.map(row => row.join(',')).join('\n');
            const blob = new Blob([csv], { type: 'text/csv' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename + '.csv';
            link.click();
            URL.revokeObjectURL(link.href);
        };
        
        window.exportToJSON = function(data, filename) {
            const json = JSON.stringify(data, null, 2);
            const blob = new Blob([json], { type: 'application/json' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename + '.json';
            link.click();
            URL.revokeObjectURL(link.href);
        };
        
        console.log('BackOffice chargé avec succès !');
    </script>
</body>
</html>
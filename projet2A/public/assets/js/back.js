// Confirmation avant suppression
function confirmDelete(message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
    return confirm(message);
}

// Notification toast
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 animate-slide-in ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Validation en temps réel des formulaires
document.addEventListener('DOMContentLoaded', function() {
    // Validation formulaire association
    const associationForm = document.getElementById('associationForm');
    if(associationForm) {
        const inputs = ['name', 'email', 'phone', 'siret', 'address', 'city', 'postal_code', 'mission'];
        
        inputs.forEach(field => {
            const input = document.getElementById(field);
            if(input) {
                input.addEventListener('blur', function() {
                    validateField(field, this.value);
                });
            }
        });
    }
    
    function validateField(field, value) {
        const errorDiv = document.querySelector(`.error-message[data-field="${field}"]`);
        if(!errorDiv) return;
        
        let error = '';
        
        switch(field) {
            case 'name':
                if(!value || value.length < 3) error = 'Le nom doit contenir au moins 3 caractères';
                break;
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if(!value || !emailRegex.test(value)) error = 'Email invalide';
                break;
            case 'phone':
                const phoneRegex = /^[0-9+\-\s]{10,}$/;
                if(!value || !phoneRegex.test(value)) error = 'Téléphone invalide (10 chiffres minimum)';
                break;
            case 'siret':
                const siretRegex = /^[0-9]{14}$/;
                if(!value || !siretRegex.test(value)) error = 'SIRET invalide (14 chiffres)';
                break;
            case 'postal_code':
                const postalRegex = /^[0-9]{5}$/;
                if(!value || !postalRegex.test(value)) error = 'Code postal invalide (5 chiffres)';
                break;
            case 'mission':
                if(!value || value.length < 20) error = 'La mission doit contenir au moins 20 caractères';
                break;
        }
        
        errorDiv.innerHTML = error;
        return !error;
    }
});

// Export CSV
function exportToCSV(data, filename = 'export.csv') {
    let csvContent = data.map(row => Object.values(row).join(',')).join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}

// Raccourcis clavier
document.addEventListener('keydown', function(e) {
    // Ctrl + S pour sauvegarder
    if((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        const form = document.querySelector('form');
        if(form) form.submit();
    }
    
    // Escape pour annuler
    if(e.key === 'Escape') {
        const cancelBtn = document.querySelector('a[href*="associations"], a[href*="dons"]');
        if(cancelBtn) window.location.href = cancelBtn.href;
    }
});

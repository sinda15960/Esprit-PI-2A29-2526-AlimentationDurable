// Validation personnalisée sans HTML5
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('donForm');
    if(form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const errors = {};
            
            // Validation association
            const association = document.getElementById('association_id');
            if(!association.value) {
                errors.association_id = "Veuillez sélectionner une association";
                isValid = false;
            }
            
            // Validation nom
            const donorName = document.getElementById('donor_name');
            if(!donorName.value || donorName.value.length < 3) {
                errors.donor_name = "Le nom doit contenir au moins 3 caractères";
                isValid = false;
            }
            
            // Validation email
            const email = document.getElementById('donor_email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!email.value || !emailRegex.test(email.value)) {
                errors.donor_email = "Email invalide";
                isValid = false;
            }
            
            // Validation type de don
            const donationType = document.querySelector('input[name="donation_type"]:checked');
            if(!donationType) {
                alert("Veuillez sélectionner un type de don");
                isValid = false;
            } else if(donationType.value === 'monetary') {
                const amount = document.getElementById('amount');
                if(!amount.value || amount.value <= 0 || amount.value > 100000) {
                    errors.amount = "Montant invalide (minimum DT)";
                    isValid = false;
                }
            } else if(donationType.value === 'food') {
                const foodType = document.getElementById('food_type');
                const quantity = document.getElementById('quantity');
                if(!foodType.value || foodType.value.length < 2) {
                    errors.food_type = "Type d'aliment invalide";
                    isValid = false;
                }
                if(!quantity.value || quantity.value <= 0 || quantity.value > 10000) {
                    errors.quantity = "Quantité invalide (entre 1 et 10000)";
                    isValid = false;
                }
            }
            
            // Validation paiement
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if(!paymentMethod) {
                alert("Veuillez sélectionner un moyen de paiement");
                isValid = false;
            }
            
            // Affichage des erreurs
            document.querySelectorAll('.error-message').forEach(el => el.innerHTML = '');
            for(const [field, message] of Object.entries(errors)) {
                const errorDiv = document.querySelector(`.error-message[data-field="${field}"]`);
                if(errorDiv) errorDiv.innerHTML = message;
            }
            
            if(!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Gestion dynamique des champs selon le type de don
    const radioButtons = document.querySelectorAll('input[name="donation_type"]');
    const monetaryFields = document.getElementById('monetaryFields');
    const foodFields = document.getElementById('foodFields');
    
    if(radioButtons) {
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.value === 'monetary') {
                    monetaryFields.style.display = 'block';
                    foodFields.style.display = 'none';
                } else if(this.value === 'food') {
                    monetaryFields.style.display = 'none';
                    foodFields.style.display = 'block';
                } else {
                    monetaryFields.style.display = 'none';
                    foodFields.style.display = 'none';
                }
            });
            
            // Déclencher au chargement
            if(radio.checked) radio.dispatchEvent(new Event('change'));
        });
    }
});

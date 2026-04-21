// ============================================
// NUTRIFLOW AI - FRONTOFFICE JAVASCRIPT
// Animations, validations, interactions
// ============================================

// Attendre le chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialisation AOS
    if(typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    }
    
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    
    if(mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        });
    }
    
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function() {
        if(window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Back to top button
    const backToTop = document.getElementById('backToTop');
    if(backToTop) {
        window.addEventListener('scroll', function() {
            if(window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
    
    // Animation au scroll pour les cartes
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.recipe-card, .category-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s, transform 0.6s';
        observer.observe(card);
    });
    
    // Newsletter validation
    const newsletterForm = document.getElementById('mainNewsletterForm');
    if(newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]');
            const consent = this.querySelector('#newsletterConsent');
            const errorDiv = document.getElementById('newsletterError');
            
            if(!email.value || !email.value.includes('@')) {
                if(errorDiv) errorDiv.textContent = 'Veuillez entrer une adresse email valide';
                return false;
            }
            
            if(consent && !consent.checked) {
                if(errorDiv) errorDiv.textContent = 'Veuillez accepter de recevoir la newsletter';
                return false;
            }
            
            alert('Merci pour votre inscription !');
            this.reset();
            if(errorDiv) errorDiv.textContent = '';
        });
    }
    
    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});

// Fonction pour sauvegarder une recette
function saveRecipe(id) {
    alert('Recette sauvegardée dans vos favoris !');
}

// Fonction pour filtrer par type
function filterByType(type) {
    window.location.href = 'index.php?action=searchRecipes&type=' + type;
}

// Fonction pour définir une valeur de recherche
function setSearchValue(value) {
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.value = value;
        document.getElementById('searchForm').submit();
    }
}

// Validation de la recherche (sans HTML5)
function validateSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchValue = searchInput.value.trim();
    const searchBtn = document.getElementById('searchBtn');
    const errorDiv = document.getElementById('searchError');
    
    // Réinitialiser
    if(errorDiv) {
        errorDiv.style.display = 'none';
        errorDiv.innerHTML = '';
    }
    
    // Vérifications
    if(searchValue === '') {
        showSearchError('Veuillez saisir un mot-clé');
        searchInput.style.borderColor = '#e74c3c';
        return false;
    }
    
    if(searchValue.length < 2) {
        showSearchError('Minimum 2 caractères');
        searchInput.style.borderColor = '#e74c3c';
        return false;
    }
    
    if(searchValue.length > 50) {
        showSearchError('Maximum 50 caractères');
        searchInput.style.borderColor = '#e74c3c';
        return false;
    }
    
    // Caractères autorisés
    const allowedPattern = /^[a-zA-Z0-9À-ÿ\s\-éèêëàâäôöûüç']+$/;
    if(!allowedPattern.test(searchValue)) {
        showSearchError('Caractères non autorisés');
        searchInput.style.borderColor = '#e74c3c';
        return false;
    }
    
    // Désactiver le bouton
    searchBtn.disabled = true;
    searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recherche...';
    searchInput.style.borderColor = '#2ecc71';
    
    return true;
}

function showSearchError(message) {
    const errorDiv = document.getElementById('searchError');
    if(errorDiv) {
        errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + message;
        errorDiv.style.display = 'block';
        
        setTimeout(() => {
            errorDiv.style.opacity = '0';
            setTimeout(() => {
                errorDiv.style.display = 'none';
                errorDiv.style.opacity = '1';
            }, 300);
        }, 5000);
    }
    
    // Réactiver le bouton
    const searchBtn = document.getElementById('searchBtn');
    if(searchBtn) {
        searchBtn.disabled = false;
        searchBtn.innerHTML = 'Rechercher';
    }
}
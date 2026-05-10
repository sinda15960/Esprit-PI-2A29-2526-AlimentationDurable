// Dark Mode Toggle avec Animation
class DarkModeManager {
    constructor() {
        this.isDark = localStorage.getItem('darkMode') === 'true';
        this.init();
    }

    init() {
        this.createToggleButton();
        this.applyTheme();
        this.observeThemeChanges();
    }

    createToggleButton() {
        const toggleHtml = `
            <div class="dark-mode-toggle" id="darkModeToggle">
                <div class="toggle-track">
                    <div class="toggle-thumb">
                        <span class="toggle-icon sun">☀️</span>
                        <span class="toggle-icon moon">🌙</span>
                    </div>
                </div>
                <span class="toggle-label">${this.isDark ? 'Dark' : 'Light'}</span>
            </div>
        `;

        const navActions = document.querySelector('.nav-actions');
        const navHost = navActions || document.querySelector('.nav-container');
        if (navHost) {
            const existingToggle = document.getElementById('darkModeToggle');
            if (existingToggle) existingToggle.remove();
            
            const toggleContainer = document.createElement('div');
            toggleContainer.innerHTML = toggleHtml;
            navHost.appendChild(toggleContainer.firstElementChild);
            
            document.getElementById('darkModeToggle').addEventListener('click', () => this.toggle());
        }
    }

    applyTheme() {
        if (this.isDark) {
            document.body.classList.add('dark-mode');
            document.documentElement.style.setProperty('--transition-speed', '0.3s');
            this.createRippleEffect();
        } else {
            document.body.classList.remove('dark-mode');
        }
        
        const label = document.querySelector('.toggle-label');
        if (label) label.textContent = this.isDark ? 'Dark' : 'Light';
        
        localStorage.setItem('darkMode', this.isDark);
    }

    createRippleEffect() {
        const elements = document.querySelectorAll('.feature-card, .stat-card, .auth-card');
        elements.forEach(el => {
            el.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
    }

    toggle() {
        // Animation de transition
        const toggle = document.getElementById('darkModeToggle');
        if (toggle) {
            toggle.style.transform = 'rotate(360deg)';
            setTimeout(() => {
                if (toggle) toggle.style.transform = '';
            }, 500);
        }
        
        this.isDark = !this.isDark;
        this.applyTheme();
        
        // Émettre un événement pour les autres composants
        document.dispatchEvent(new CustomEvent('themeChanged', { detail: { isDark: this.isDark } }));
    }

    observeThemeChanges() {
        document.addEventListener('themeChanged', (e) => {
            console.log(`Theme changed to ${e.detail.isDark ? 'dark' : 'light'}`);
        });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    window.darkModeManager = new DarkModeManager();
});

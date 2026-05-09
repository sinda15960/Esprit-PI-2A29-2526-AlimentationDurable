<!-- Daily Inspiration Quote Widget -->
<div class="daily-quote" id="dailyQuote">
    <div class="quote-icon">💬</div>
    <div class="quote-content">
        <p class="quote-text" id="quoteText">Loading your daily inspiration...</p>
        <p class="quote-author" id="quoteAuthor">- NutriFlow AI</p>
    </div>
    <button class="quote-refresh" onclick="refreshQuote()" title="Get new quote">
        <span>🔄</span>
    </button>
</div>

<style>
.daily-quote {
    background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
    border-radius: 20px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s;
}

body.dark-mode .daily-quote {
    background: linear-gradient(135deg, #2e1065, #4c1d95);
}

.quote-icon {
    font-size: 2rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.quote-content {
    flex: 1;
}

.quote-text {
    font-size: 0.95rem;
    color: #4c1d95;
    font-style: italic;
    margin: 0;
    line-height: 1.5;
}

body.dark-mode .quote-text {
    color: #d8b4fe;
}

.quote-author {
    font-size: 0.75rem;
    color: #7c3aed;
    margin: 0.25rem 0 0;
    opacity: 0.8;
}

.quote-refresh {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s;
    padding: 0.5rem;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quote-refresh:hover {
    background: rgba(124, 58, 237, 0.1);
    transform: rotate(180deg);
}

/* Animation de transition */
.quote-fade-out {
    animation: fadeOut 0.3s ease forwards;
}

.quote-fade-in {
    animation: fadeIn 0.3s ease forwards;
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(-10px); }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateX(10px); }
    to { opacity: 1; transform: translateX(0); }
}

@media (max-width: 768px) {
    .daily-quote {
        flex-direction: column;
        text-align: center;
    }
    
    .quote-text {
        font-size: 0.85rem;
    }
}
</style>

<script>
// Daily Quote Manager
class QuoteManager {
    constructor() {
        this.quotes = [
            { text: "The greatest wealth is health.", author: "Virgil" },
            { text: "Let food be thy medicine and medicine be thy food.", author: "Hippocrates" },
            { text: "Take care of your body. It's the only place you have to live.", author: "Jim Rohn" },
            { text: "Healthy eating is a form of self-respect.", author: "Unknown" },
            { text: "What you eat in private, you wear in public.", author: "Unknown" },
            { text: "To eat is a necessity, but to eat intelligently is an art.", author: "La Rochefoucauld" },
            { text: "The food you eat can be either the safest and most powerful form of medicine or the slowest form of poison.", author: "Ann Wigmore" },
            { text: "One cannot think well, love well, sleep well, if one has not dined well.", author: "Virginia Woolf" },
            { text: "Your diet is a bank account. Good food choices are good investments.", author: "Bethenny Frankel" },
            { text: "Every time you eat or drink, you are either feeding disease or fighting it.", author: "Heather Morgan" },
            { text: "Small steps every day lead to big results.", author: "NutriFlow AI" },
            { text: "Consistency over intensity. One day at a time!", author: "NutriFlow AI" },
            { text: "Your body deserves the best fuel. You're doing amazing!", author: "NutriFlow AI" },
            { text: "Progress, not perfection. Celebrate small wins!", author: "NutriFlow AI" },
            { text: "You are what you eat. Make it count today!", author: "NutriFlow AI" }
        ];
        this.currentQuote = null;
        this.init();
    }

    init() {
        this.loadDailyQuote();
        this.animateQuote();
    }

    loadDailyQuote() {
        const today = new Date().toDateString();
        let savedQuote = localStorage.getItem('dailyQuote');
        let savedDate = localStorage.getItem('quoteDate');
        
        if (savedDate !== today || !savedQuote) {
            const randomIndex = Math.floor(Math.random() * this.quotes.length);
            savedQuote = JSON.stringify(this.quotes[randomIndex]);
            localStorage.setItem('dailyQuote', savedQuote);
            localStorage.setItem('quoteDate', today);
        }
        
        this.currentQuote = JSON.parse(savedQuote);
        this.displayQuote();
    }

    displayQuote() {
        const quoteText = document.getElementById('quoteText');
        const quoteAuthor = document.getElementById('quoteAuthor');
        
        if (quoteText && quoteAuthor) {
            quoteText.textContent = this.currentQuote.text;
            quoteAuthor.textContent = `- ${this.currentQuote.author}`;
        }
    }

    refreshQuote() {
        const randomIndex = Math.floor(Math.random() * this.quotes.length);
        this.currentQuote = this.quotes[randomIndex];
        
        const quoteText = document.getElementById('quoteText');
        const quoteAuthor = document.getElementById('quoteAuthor');
        
        if (quoteText && quoteAuthor) {
            quoteText.classList.add('quote-fade-out');
            
            setTimeout(() => {
                quoteText.textContent = this.currentQuote.text;
                quoteAuthor.textContent = `- ${this.currentQuote.author}`;
                quoteText.classList.remove('quote-fade-out');
                quoteText.classList.add('quote-fade-in');
                
                setTimeout(() => {
                    quoteText.classList.remove('quote-fade-in');
                }, 300);
            }, 300);
        }
        
        // Sauvegarder pour la journée
        const today = new Date().toDateString();
        localStorage.setItem('dailyQuote', JSON.stringify(this.currentQuote));
        localStorage.setItem('quoteDate', today);
    }

    animateQuote() {
        setInterval(() => {
            const icon = document.querySelector('.quote-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    if (icon) icon.style.transform = 'scale(1)';
                }, 500);
            }
        }, 30000);
    }
}

function refreshQuote() {
    if (window.quoteManager) {
        window.quoteManager.refreshQuote();
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    window.quoteManager = new QuoteManager();
});
</script>

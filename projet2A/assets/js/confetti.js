// Confetti Effect Manager
class ConfettiManager {
    constructor() {
        this.canvas = null;
        this.ctx = null;
        this.particles = [];
        this.animationId = null;
    }

    showConfetti(options = {}) {
        const defaults = {
            duration: 3000,
            particleCount: 150,
            colors: ['#16a34a', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6', '#ec4899'],
            spread: 60,
            origin: { x: 0.5, y: 0.5 }
        };
        
        const settings = { ...defaults, ...options };
        
        this.createCanvas();
        this.createParticles(settings);
        this.animate();
        
        setTimeout(() => this.cleanup(), settings.duration);
    }

    createCanvas() {
        if (this.canvas) return;
        
        this.canvas = document.createElement('canvas');
        this.canvas.style.position = 'fixed';
        this.canvas.style.top = '0';
        this.canvas.style.left = '0';
        this.canvas.style.width = '100%';
        this.canvas.style.height = '100%';
        this.canvas.style.pointerEvents = 'none';
        this.canvas.style.zIndex = '10000';
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
        
        this.ctx = this.canvas.getContext('2d');
        document.body.appendChild(this.canvas);
    }

    createParticles(settings) {
        this.particles = [];
        
        for (let i = 0; i < settings.particleCount; i++) {
            const angle = Math.random() * Math.PI * 2;
            const velocity = 5 + Math.random() * 10;
            const vx = Math.cos(angle) * velocity * (Math.random() - 0.5);
            const vy = Math.sin(angle) * velocity * (Math.random() - 0.5) - 8;
            
            this.particles.push({
                x: settings.origin.x * this.canvas.width,
                y: settings.origin.y * this.canvas.height,
                vx: vx,
                vy: vy,
                size: 5 + Math.random() * 8,
                color: settings.colors[Math.floor(Math.random() * settings.colors.length)],
                rotation: Math.random() * 360,
                rotationSpeed: (Math.random() - 0.5) * 10,
                gravity: 0.3,
                opacity: 1,
                life: 1
            });
        }
    }

    animate() {
        if (!this.ctx) return;
        
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        let allDead = true;
        
        for (let i = 0; i < this.particles.length; i++) {
            const p = this.particles[i];
            
            if (p.opacity <= 0) continue;
            
            allDead = false;
            
            p.x += p.vx;
            p.y += p.vy;
            p.vy += p.gravity;
            p.rotation += p.rotationSpeed;
            p.life -= 0.02;
            p.opacity = p.life;
            
            this.ctx.save();
            this.ctx.translate(p.x, p.y);
            this.ctx.rotate(p.rotation * Math.PI / 180);
            this.ctx.globalAlpha = p.opacity;
            this.ctx.fillStyle = p.color;
            this.ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);
            this.ctx.restore();
        }
        
        if (allDead) {
            this.cleanup();
        } else {
            this.animationId = requestAnimationFrame(() => this.animate());
        }
    }

    cleanup() {
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
            this.animationId = null;
        }
        
        if (this.canvas) {
            this.canvas.remove();
            this.canvas = null;
            this.ctx = null;
        }
        
        this.particles = [];
    }
}

// Fonction d'explosion de confettis
function burstConfetti() {
    const confetti = new ConfettiManager();
    
    // Explosion centrale
    confetti.showConfetti({
        duration: 3000,
        particleCount: 200,
        origin: { x: 0.5, y: 0.5 }
    });
    
    // Explosions latérales
    setTimeout(() => {
        const confetti2 = new ConfettiManager();
        confetti2.showConfetti({
            duration: 2000,
            particleCount: 100,
            origin: { x: 0.2, y: 0.6 }
        });
    }, 100);
    
    setTimeout(() => {
        const confetti3 = new ConfettiManager();
        confetti3.showConfetti({
            duration: 2000,
            particleCount: 100,
            origin: { x: 0.8, y: 0.6 }
        });
    }, 200);
}

// Initialisation - Vérifier si l'utilisateur vient de s'inscrire
document.addEventListener('DOMContentLoaded', () => {
    if (sessionStorage.getItem('justRegistered') === 'true') {
        burstConfetti();
        sessionStorage.removeItem('justRegistered');
        
        // Afficher un message de bienvenue
        const welcomeMessage = document.createElement('div');
        welcomeMessage.className = 'welcome-message';
        welcomeMessage.innerHTML = `
            <div class="welcome-content">
                <span class="welcome-icon">🎉</span>
                <div>
                    <strong>Welcome to NutriFlow AI!</strong>
                    <p>Start your healthy journey today!</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()">✖</button>
            </div>
        `;
        welcomeMessage.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 10001;
            animation: slideInRight 0.3s ease;
        `;
        document.body.appendChild(welcomeMessage);
        setTimeout(() => welcomeMessage.remove(), 5000);
    }
});

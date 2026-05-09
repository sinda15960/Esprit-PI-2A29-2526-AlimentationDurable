<!-- Globe 3D Connections View -->
<div class="globe-container">
    <div class="globe-header">
        <h2>🌍 Real-Time Connections Globe</h2>
        <div class="globe-stats-panel">
            <div class="stat">
                <span class="stat-value" id="liveConnections">0</span>
                <span class="stat-label">Live Connections</span>
            </div>
            <div class="stat">
                <span class="stat-value" id="totalToday">0</span>
                <span class="stat-label">Today's Connections</span>
            </div>
            <div class="stat">
                <span class="stat-value" id="activeCountries">0</span>
                <span class="stat-label">Active Countries</span>
            </div>
        </div>
        <button class="toggle-fullscreen" onclick="toggleFullscreen()">⛶ Fullscreen</button>
    </div>
    
    <div id="globeCanvas" class="globe-canvas"></div>
    
    <div class="connection-feed">
        <h4>📡 Live Activity Feed</h4>
        <div id="connectionFeed" class="feed-list">
            <div class="feed-placeholder">Waiting for connections...</div>
        </div>
    </div>
</div>

<style>
.globe-container {
    background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3e 100%);
    border-radius: 20px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.globe-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.globe-header h2 {
    color: white;
    margin: 0;
    text-shadow: 0 0 10px rgba(34,197,94,0.5);
}

.globe-stats-panel {
    display: flex;
    gap: 1.5rem;
    background: rgba(0,0,0,0.4);
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    backdrop-filter: blur(10px);
}

.globe-stats-panel .stat {
    text-align: center;
}

.globe-stats-panel .stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: #22c55e;
}

.globe-stats-panel .stat-label {
    font-size: 0.7rem;
    color: #94a3b8;
}

.toggle-fullscreen {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
}

.toggle-fullscreen:hover {
    background: rgba(255,255,255,0.2);
    transform: scale(1.05);
}

.globe-canvas {
    width: 100%;
    height: 500px;
    border-radius: 16px;
    overflow: hidden;
    background: #000;
    cursor: grab;
}

.globe-canvas:active {
    cursor: grabbing;
}

.connection-feed {
    margin-top: 1.5rem;
    background: rgba(0,0,0,0.3);
    border-radius: 16px;
    padding: 1rem;
    backdrop-filter: blur(10px);
}

.connection-feed h4 {
    color: #94a3b8;
    margin-bottom: 0.75rem;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.feed-list {
    max-height: 150px;
    overflow-y: auto;
    font-size: 0.8rem;
}

.feed-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    animation: slideInRight 0.3s ease;
}

.feed-item .feed-icon {
    width: 30px;
    height: 30px;
    background: rgba(34,197,94,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.feed-item .feed-text {
    flex: 1;
    color: #e2e8f0;
}

.feed-item .feed-time {
    font-size: 0.7rem;
    color: #64748b;
}

.feed-item.new {
    background: rgba(34,197,94,0.1);
    border-left: 3px solid #22c55e;
}

.feed-placeholder {
    text-align: center;
    color: #64748b;
    padding: 1rem;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.feed-list::-webkit-scrollbar {
    width: 4px;
}

.feed-list::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
    border-radius: 4px;
}

.feed-list::-webkit-scrollbar-thumb {
    background: #22c55e;
    border-radius: 4px;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<script>
// ========== GLOBE 3D CONFIGURATION ==========
let scene, camera, renderer, globe, globeGroup, rafId;
let markers = [];
let particles = [];
let connections = [];
let liveConnectionsCount = 0;
let totalTodayCount = 0;
let activeCountriesSet = new Set();

// Locations données (pays et coordonnées)
const locations = [
    { name: 'USA', lat: 37.0902, lon: -95.7129, code: 'US', color: '#ef4444' },
    { name: 'Canada', lat: 56.1304, lon: -106.3468, code: 'CA', color: '#3b82f6' },
    { name: 'Mexico', lat: 23.6345, lon: -102.5528, code: 'MX', color: '#f97316' },
    { name: 'Brazil', lat: -14.2350, lon: -51.9253, code: 'BR', color: '#10b981' },
    { name: 'Argentina', lat: -38.4161, lon: -63.6167, code: 'AR', color: '#f59e0b' },
    { name: 'UK', lat: 55.3781, lon: -3.4360, code: 'GB', color: '#8b5cf6' },
    { name: 'France', lat: 46.603354, lon: 1.888334, code: 'FR', color: '#ec4899' },
    { name: 'Germany', lat: 51.1657, lon: 10.4515, code: 'DE', color: '#06b6d4' },
    { name: 'Spain', lat: 40.4637, lon: -3.7492, code: 'ES', color: '#f97316' },
    { name: 'Italy', lat: 41.8719, lon: 12.5674, code: 'IT', color: '#14b8a6' },
    { name: 'Russia', lat: 61.5240, lon: 105.3188, code: 'RU', color: '#a855f7' },
    { name: 'China', lat: 35.8617, lon: 104.1954, code: 'CN', color: '#ef4444' },
    { name: 'Japan', lat: 36.2048, lon: 138.2529, code: 'JP', color: '#eab308' },
    { name: 'South Korea', lat: 35.9078, lon: 127.7669, code: 'KR', color: '#3b82f6' },
    { name: 'India', lat: 20.5937, lon: 78.9629, code: 'IN', color: '#f97316' },
    { name: 'Australia', lat: -25.2744, lon: 133.7751, code: 'AU', color: '#10b981' },
    { name: 'South Africa', lat: -30.5595, lon: 22.9375, code: 'ZA', color: '#8b5cf6' },
    { name: 'Egypt', lat: 26.8206, lon: 30.8025, code: 'EG', color: '#f59e0b' },
    { name: 'Nigeria', lat: 9.0820, lon: 8.6753, code: 'NG', color: '#06b6d4' },
    { name: 'Turkey', lat: 38.9637, lon: 35.2433, code: 'TR', color: '#ec4899' },
];

function initGlobe() {
    const container = document.getElementById('globeCanvas');
    const width = container.clientWidth;
    const height = 500;
    
    // Setup scene
    scene = new THREE.Scene();
    scene.background = new THREE.Color(0x0a0a2a);
    scene.fog = new THREE.FogExp2(0x0a0a2a, 0.0005);
    
    // Setup camera
    camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
    camera.position.z = 3.5;
    camera.position.y = 0.5;
    
    // Setup renderer
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(window.devicePixelRatio);
    container.innerHTML = '';
    container.appendChild(renderer.domElement);
    
    // Add stars background
    const starGeometry = new THREE.BufferGeometry();
    const starCount = 2000;
    const starPositions = new Float32Array(starCount * 3);
    for (let i = 0; i < starCount; i++) {
        starPositions[i*3] = (Math.random() - 0.5) * 2000;
        starPositions[i*3+1] = (Math.random() - 0.5) * 1000;
        starPositions[i*3+2] = -50 + Math.random() * -100;
    }
    starGeometry.setAttribute('position', new THREE.BufferAttribute(starPositions, 3));
    const starMaterial = new THREE.PointsMaterial({ color: 0xffffff, size: 0.3 });
    const stars = new THREE.Points(starGeometry, starMaterial);
    scene.add(stars);
    
    // Create globe sphere
    const geometry = new THREE.SphereGeometry(1, 64, 64);
    const textureLoader = new THREE.TextureLoader();
    
    // Load earth texture (high res)
    const earthTexture = createCanvasTexture();
    const material = new THREE.MeshPhongMaterial({
        map: earthTexture,
        shininess: 5,
        specular: new THREE.Color(0x111111)
    });
    
    globe = new THREE.Mesh(geometry, material);
    scene.add(globe);
    
    // Add atmosphere glow
    const atmosphereGeometry = new THREE.SphereGeometry(1.02, 64, 64);
    const atmosphereMaterial = new THREE.MeshPhongMaterial({
        color: 0x22c55e,
        transparent: true,
        opacity: 0.1
    });
    const atmosphere = new THREE.Mesh(atmosphereGeometry, atmosphereMaterial);
    scene.add(atmosphere);
    
    // Add lights
    const ambientLight = new THREE.AmbientLight(0x404040);
    scene.add(ambientLight);
    
    const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
    directionalLight.position.set(5, 3, 5);
    scene.add(directionalLight);
    
    const backLight = new THREE.PointLight(0x22c55e, 0.3);
    backLight.position.set(-2, -1, -3);
    scene.add(backLight);
    
    // Add markers for each location
    locations.forEach(loc => {
        const marker = createMarker(loc);
        scene.add(marker);
        markers.push({ marker, loc });
    });
    
    // Animation loop
    function animate() {
        rafId = requestAnimationFrame(animate);
        
        // Auto-rotate globe slowly
        globe.rotation.y += 0.001;
        atmosphere.rotation.y += 0.001;
        stars.rotation.y += 0.0002;
        
        // Animate particles
        particles.forEach(p => {
            p.mesh.position.x += p.velocity.x;
            p.mesh.position.y += p.velocity.y;
            p.mesh.position.z += p.velocity.z;
            
            // Fade out
            p.life -= 0.02;
            p.mesh.material.opacity = p.life;
            
            if (p.life <= 0) {
                scene.remove(p.mesh);
                particles = particles.filter(part => part !== p);
            }
        });
        
        renderer.render(scene, camera);
    }
    
    animate();
    
    // Start simulating connections
    startConnectionSimulation();
    
    // Handle resize
    window.addEventListener('resize', () => {
        const newWidth = container.clientWidth;
        camera.aspect = newWidth / height;
        camera.updateProjectionMatrix();
        renderer.setSize(newWidth, height);
    });
}

function createCanvasTexture() {
    // Create a texture with country outlines (simplified)
    const canvas = document.createElement('canvas');
    canvas.width = 2048;
    canvas.height = 1024;
    const ctx = canvas.getContext('2d');
    
    // Base color (ocean)
    ctx.fillStyle = '#1a3a5c';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Draw simplified continents
    ctx.fillStyle = '#2d5a3f';
    // North America
    ctx.fillRect(200, 200, 400, 300);
    // South America
    ctx.fillRect(350, 500, 200, 300);
    // Europe
    ctx.fillRect(900, 200, 300, 250);
    // Africa
    ctx.fillRect(950, 450, 300, 300);
    // Asia
    ctx.fillRect(1300, 200, 500, 350);
    // Australia
    ctx.fillRect(1500, 700, 250, 200);
    
    // Add some green variation
    ctx.fillStyle = '#3a7a4f';
    ctx.fillRect(250, 250, 150, 100);
    ctx.fillRect(1000, 250, 150, 100);
    ctx.fillRect(1400, 250, 200, 100);
    
    const texture = new THREE.CanvasTexture(canvas);
    texture.wrapS = THREE.RepeatWrapping;
    texture.wrapT = THREE.RepeatWrapping;
    return texture;
}

function createMarker(location) {
    const group = new THREE.Group();
    
    // Convert lat/lon to 3D coordinates
    const radius = 1.02;
    const latRad = location.lat * Math.PI / 180;
    const lonRad = location.lon * Math.PI / 180;
    const x = radius * Math.cos(latRad) * Math.cos(lonRad);
    const y = radius * Math.sin(latRad);
    const z = radius * Math.cos(latRad) * Math.sin(lonRad);
    
    group.position.set(x, y, z);
    
    // Create marker ring
    const ringGeo = new THREE.SphereGeometry(0.03, 16, 16);
    const ringMat = new THREE.MeshStandardMaterial({ color: location.color, emissive: location.color, emissiveIntensity: 0.5 });
    const ring = new THREE.Mesh(ringGeo, ringMat);
    group.add(ring);
    
    // Add pulse effect
    const pulseGeo = new THREE.SphereGeometry(0.06, 16, 16);
    const pulseMat = new THREE.MeshStandardMaterial({ color: location.color, transparent: true, opacity: 0.5 });
    const pulse = new THREE.Mesh(pulseGeo, pulseMat);
    group.add(pulse);
    
    // Animate pulse
    let scale = 1;
    let direction = 1;
    setInterval(() => {
        scale += direction * 0.05;
        if (scale > 1.5 || scale < 1) direction *= -1;
        pulse.scale.set(scale, scale, scale);
        pulse.material.opacity = 0.5 * (2 - scale);
    }, 100);
    
    return group;
}

function createConnectionLine(lat1, lon1, lat2, lon2, color) {
    const radius = 1.02;
    const points = [];
    
    // Interpolate between two points
    for (let t = 0; t <= 1; t += 0.05) {
        const lat = lat1 * (1 - t) + lat2 * t;
        const lon = lon1 * (1 - t) + lon2 * t;
        const latRad = lat * Math.PI / 180;
        const lonRad = lon * Math.PI / 180;
        const x = radius * Math.cos(latRad) * Math.cos(lonRad);
        const y = radius * Math.sin(latRad);
        const z = radius * Math.cos(latRad) * Math.sin(lonRad);
        points.push(new THREE.Vector3(x, y, z));
    }
    
    const geometry = new THREE.BufferGeometry().setFromPoints(points);
    const material = new THREE.LineBasicMaterial({ color: color });
    const line = new THREE.Line(geometry, material);
    return line;
}

function addParticle(x, y, z, color) {
    const particleGeo = new THREE.SphereGeometry(0.008, 8, 8);
    const particleMat = new THREE.MeshStandardMaterial({ color: color, emissive: color, emissiveIntensity: 0.8 });
    const particle = new THREE.Mesh(particleGeo, particleMat);
    particle.position.set(x, y, z);
    scene.add(particle);
    
    particles.push({
        mesh: particle,
        velocity: {
            x: (Math.random() - 0.5) * 0.02,
            y: (Math.random() - 0.5) * 0.02,
            z: (Math.random() - 0.5) * 0.02
        },
        life: 1
    });
}

function addToFeed(country, isLogin = true) {
    const feedContainer = document.getElementById('connectionFeed');
    const feedItem = document.createElement('div');
    feedItem.className = 'feed-item new';
    
    const now = new Date();
    const timeStr = now.toLocaleTimeString();
    
    const action = isLogin ? '🔌 logged in from' : '📱 active on';
    
    feedItem.innerHTML = `
        <div class="feed-icon">${isLogin ? '🌍' : '📱'}</div>
        <div class="feed-text">
            <strong>User from ${country}</strong> ${action} NutriFlow AI
        </div>
        <div class="feed-time">${timeStr}</div>
    `;
    
    feedContainer.insertBefore(feedItem, feedContainer.firstChild);
    
    // Remove old items (keep max 20)
    while (feedContainer.children.length > 20) {
        feedContainer.removeChild(feedContainer.lastChild);
    }
    
    // Remove "new" class after animation
    setTimeout(() => {
        feedItem.classList.remove('new');
    }, 1000);
}

function startConnectionSimulation() {
    // Simulate live connections every 2-5 seconds
    setInterval(() => {
        // Random location
        const randomIndex = Math.floor(Math.random() * locations.length);
        const location = locations[randomIndex];
        
        // Increment counters
        liveConnectionsCount++;
        totalTodayCount++;
        activeCountriesSet.add(location.name);
        
        // Update UI
        document.getElementById('liveConnections').textContent = liveConnectionsCount;
        document.getElementById('totalToday').textContent = totalTodayCount;
        document.getElementById('activeCountries').textContent = activeCountriesSet.size;
        
        // Add to feed
        addToFeed(location.name, true);
        
        // Create flying particle from random source to this location
        const randomSource = locations[Math.floor(Math.random() * locations.length)];
        const destLatRad = location.lat * Math.PI / 180;
        const destLonRad = location.lon * Math.PI / 180;
        const radius = 1.02;
        const destX = radius * Math.cos(destLatRad) * Math.cos(destLonRad);
        const destY = radius * Math.sin(destLatRad);
        const destZ = radius * Math.cos(destLatRad) * Math.sin(destLonRad);
        
        addParticle(destX, destY, destZ, location.color);
        
        // Decrement live connections after 5-10 seconds (simulate logout)
        setTimeout(() => {
            liveConnectionsCount = Math.max(0, liveConnectionsCount - 1);
            document.getElementById('liveConnections').textContent = liveConnectionsCount;
        }, 5000 + Math.random() * 5000);
        
    }, 3000);
    
    // Also simulate page views (device activity)
    setInterval(() => {
        const randomIndex = Math.floor(Math.random() * locations.length);
        const location = locations[randomIndex];
        addToFeed(location.name, false);
        
        totalTodayCount++;
        document.getElementById('totalToday').textContent = totalTodayCount;
    }, 8000);
}

function toggleFullscreen() {
    const container = document.querySelector('.globe-container');
    if (!document.fullscreenElement) {
        container.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initGlobe);
</script>

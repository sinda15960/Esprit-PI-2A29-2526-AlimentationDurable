<!-- Avatar Generator -->
<div class="avatar-generator" id="avatarGenerator">
    <div class="avatar-display">
        <div class="avatar-preview" id="avatarPreview">
            <div class="avatar-content" id="avatarContent">
                <span class="avatar-emoji" id="avatarEmoji">👤</span>
                <img id="avatarImage" class="avatar-image" src="" alt="Avatar" style="display: none;">
            </div>
        </div>
        <div class="avatar-name" id="avatarName"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></div>
    </div>
    
    <div class="avatar-options">
        <button class="avatar-option" onclick="generateRandomAvatar()">
            <span>🎲</span> Random
        </button>
        <button class="avatar-option" onclick="openAvatarUpload()">
            <span>📸</span> Upload Photo
        </button>
        <button class="avatar-option" onclick="openAvatarGallery()">
            <span>🎨</span> Gallery
        </button>
    </div>
    
    <div class="avatar-upload" id="avatarUpload" style="display: none;">
        <p class="upload-instruction">Choose a photo from your device</p>
        <input type="file" id="avatarFile" accept="image/jpeg,image/png,image/gif" onchange="previewAndUpload(this)">
        <div class="upload-preview" id="uploadPreview"></div>
        <div class="upload-actions">
            <button class="upload-btn" onclick="saveUploadedPhoto()">💾 Save Photo</button>
            <button class="upload-btn cancel" onclick="closeUpload()">Cancel</button>
        </div>
    </div>
    
    <div class="avatar-gallery" id="avatarGallery" style="display: none;">
        <p class="gallery-instruction">Click on an emoji to use it as your avatar</p>
        <div class="gallery-grid" id="galleryGrid"></div>
        <button class="gallery-close" onclick="closeGallery()">Close</button>
    </div>
</div>

<!-- Style pour l'avatar principal (dans le header du profil) -->
<style>
/* Style pour l'avatar dans le header du profil */
.profile-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #16a34a, #14532d);
    border-radius: 50%;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.profile-avatar .avatar-emoji-main {
    font-size: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.profile-avatar .avatar-image-main {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}

/* Style pour le générateur */
.avatar-generator {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
}

body.dark-mode .avatar-generator {
    background: #1e293b;
}

.avatar-display {
    margin-bottom: 1rem;
}

.avatar-preview {
    width: 80px;
    height: 80px;
    margin: 0 auto 0.5rem;
    border-radius: 50%;
    overflow: hidden;
    position: relative;
    background: linear-gradient(135deg, #16a34a, #14532d);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s;
    cursor: pointer;
}

.avatar-preview:hover {
    transform: scale(1.05);
}

.avatar-content {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.avatar-emoji {
    font-size: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}

.avatar-name {
    font-weight: 600;
    color: #1e293b;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.avatar-options {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
    margin: 1rem 0;
    flex-wrap: wrap;
}

.avatar-option {
    background: linear-gradient(135deg, #16a34a, #14532d);
    border: none;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
}

.avatar-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22,163,74,0.3);
}

.avatar-upload, .avatar-gallery {
    margin-top: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
}

.upload-preview {
    width: 80px;
    height: 80px;
    margin: 0.5rem auto;
    border-radius: 50%;
    overflow: hidden;
    background: #e2e8f0;
}

.upload-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.upload-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.upload-btn {
    background: #16a34a;
    color: white;
    border: none;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    cursor: pointer;
    font-size: 0.8rem;
}

.upload-btn.cancel {
    background: #e2e8f0;
    color: #475569;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0.5rem;
    margin-bottom: 1rem;
    max-height: 200px;
    overflow-y: auto;
}

.gallery-item {
    aspect-ratio: 1 / 1;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.2s;
}

.gallery-item:hover {
    transform: scale(1.1);
    background: #16a34a;
}

.gallery-close {
    background: #e2e8f0;
    border: none;
    padding: 0.3rem 1rem;
    border-radius: 50px;
    cursor: pointer;
    width: 100%;
}

.avatar-toast {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #16a34a;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 50px;
    z-index: 10000;
    animation: fadeInUp 0.3s ease;
    font-size: 0.85rem;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}
</style>

<script>
// ========== AVATAR MANAGER ==========
let avatarManager;
let uploadedPhotoData = null;

class AvatarManager {
    constructor(username) {
        this.username = username;
        this.loadAvatar();
    }

    loadAvatar() {
        const savedAvatar = localStorage.getItem('userAvatar');
        if (savedAvatar) {
            try {
                this.currentAvatar = JSON.parse(savedAvatar);
            } catch(e) {
                this.setDefaultAvatar();
            }
        } else {
            this.setDefaultAvatar();
        }
        this.displayAvatar();
        this.updateMainProfileAvatar(); // Mettre à jour l'avatar principal
    }

    setDefaultAvatar() {
        this.currentAvatar = {
            type: 'initials',
            text: this.getInitials(),
            color: this.getColorFromName()
        };
    }

    getInitials() {
        if (this.username && this.username !== 'User') {
            return this.username.substring(0, 2).toUpperCase();
        }
        return '👤';
    }

    getColorFromName() {
        const colors = ['#16a34a', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#6366f1'];
        const index = this.username ? this.username.length % colors.length : 0;
        return colors[index];
    }

    displayAvatar() {
        const emojiSpan = document.getElementById('avatarEmoji');
        const avatarImage = document.getElementById('avatarImage');
        
        if (!emojiSpan || !avatarImage) return;
        
        // Reset
        emojiSpan.style.display = 'flex';
        avatarImage.style.display = 'none';
        avatarImage.src = '';
        
        if (this.currentAvatar.type === 'initials') {
            emojiSpan.innerHTML = this.currentAvatar.text;
            emojiSpan.style.backgroundColor = this.currentAvatar.color;
            emojiSpan.style.fontSize = '1.2rem';
            emojiSpan.style.fontWeight = 'bold';
            emojiSpan.style.color = 'white';
        } 
        else if (this.currentAvatar.type === 'emoji') {
            emojiSpan.innerHTML = this.currentAvatar.emoji;
            emojiSpan.style.backgroundColor = 'transparent';
            emojiSpan.style.fontSize = '2.5rem';
            emojiSpan.style.fontWeight = 'normal';
            emojiSpan.style.color = 'white';
        } 
        else if (this.currentAvatar.type === 'photo' && this.currentAvatar.url) {
            emojiSpan.style.display = 'none';
            avatarImage.style.display = 'block';
            avatarImage.src = this.currentAvatar.url;
        }
        
        this.saveToLocal();
    }

    // Fonction pour mettre à jour l'avatar dans l'en-tête du profil
    updateMainProfileAvatar() {
        const mainAvatarDiv = document.querySelector('.profile-avatar');
        if (!mainAvatarDiv) return;
        
        // Vider le contenu existant
        mainAvatarDiv.innerHTML = '';
        
        if (this.currentAvatar.type === 'initials') {
            const span = document.createElement('span');
            span.className = 'avatar-emoji-main';
            span.textContent = this.currentAvatar.text;
            span.style.backgroundColor = this.currentAvatar.color;
            span.style.fontSize = '1.5rem';
            span.style.fontWeight = 'bold';
            span.style.color = 'white';
            span.style.display = 'flex';
            span.style.alignItems = 'center';
            span.style.justifyContent = 'center';
            span.style.width = '100%';
            span.style.height = '100%';
            mainAvatarDiv.appendChild(span);
        } 
        else if (this.currentAvatar.type === 'emoji') {
            const span = document.createElement('span');
            span.className = 'avatar-emoji-main';
            span.textContent = this.currentAvatar.emoji;
            span.style.fontSize = '3rem';
            span.style.display = 'flex';
            span.style.alignItems = 'center';
            span.style.justifyContent = 'center';
            span.style.width = '100%';
            span.style.height = '100%';
            mainAvatarDiv.appendChild(span);
        } 
        else if (this.currentAvatar.type === 'photo' && this.currentAvatar.url) {
            const img = document.createElement('img');
            img.className = 'avatar-image-main';
            img.src = this.currentAvatar.url;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            mainAvatarDiv.appendChild(img);
        }
    }

    saveToLocal() {
        localStorage.setItem('userAvatar', JSON.stringify(this.currentAvatar));
    }

    setAvatar(avatarData) {
        this.currentAvatar = avatarData;
        this.displayAvatar();
        this.updateMainProfileAvatar(); // Important : met à jour l'avatar principal
        this.showToast('✅ Avatar updated!');
    }

    showToast(message) {
        const existing = document.querySelectorAll('.avatar-toast');
        existing.forEach(t => t.remove());
        
        const toast = document.createElement('div');
        toast.className = 'avatar-toast';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }
}

// ========== INIT ==========
document.addEventListener('DOMContentLoaded', function() {
    const nameElement = document.getElementById('avatarName');
    const username = nameElement ? nameElement.textContent.trim() : 'User';
    avatarManager = new AvatarManager(username);
});

// ========== RANDOM ==========
function generateRandomAvatar() {
    const emojis = ['😊', '🌟', '💪', '🎯', '🍎', '🥗', '🏃', '🧘', '😎', '🔥', '⭐', '🏆', '🥑', '🍕', '🥦', '🍇', '🐱', '🐶', '🦊', '🐼'];
    const randomEmoji = emojis[Math.floor(Math.random() * emojis.length)];
    
    avatarManager.setAvatar({
        type: 'emoji',
        emoji: randomEmoji
    });
}

// ========== UPLOAD ==========
function openAvatarUpload() {
    document.getElementById('avatarUpload').style.display = 'block';
    document.getElementById('avatarGallery').style.display = 'none';
    document.getElementById('uploadPreview').innerHTML = '';
    document.getElementById('avatarFile').value = '';
    uploadedPhotoData = null;
}

function previewAndUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.getElementById('uploadPreview');
            previewDiv.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            uploadedPhotoData = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function saveUploadedPhoto() {
    if (uploadedPhotoData) {
        avatarManager.setAvatar({
            type: 'photo',
            url: uploadedPhotoData
        });
        closeUpload();
    } else {
        alert('Please select an image first');
    }
}

function closeUpload() {
    document.getElementById('avatarUpload').style.display = 'none';
    document.getElementById('uploadPreview').innerHTML = '';
    document.getElementById('avatarFile').value = '';
    uploadedPhotoData = null;
}

// ========== GALLERY ==========
function openAvatarGallery() {
    const grid = document.getElementById('galleryGrid');
    const emojis = ['😊', '😎', '🥗', '🍎', '🏃', '🧘', '💪', '🎯', '🔥', '⭐', '🏆', '🥑', '🐱', '🐶', '🦊', '🐼', '❤️', '🌟', '🍕', '🥦', '🍇', '🌈', '⚡', '🎨'];
    
    grid.innerHTML = '';
    for (let emoji of emojis) {
        const item = document.createElement('div');
        item.className = 'gallery-item';
        item.innerHTML = emoji;
        item.onclick = (function(e) { selectEmojiAvatar(emoji); });
        grid.appendChild(item);
    }
    
    document.getElementById('avatarGallery').style.display = 'block';
    document.getElementById('avatarUpload').style.display = 'none';
}

function selectEmojiAvatar(emoji) {
    avatarManager.setAvatar({
        type: 'emoji',
        emoji: emoji
    });
    closeGallery();
}

function closeGallery() {
    document.getElementById('avatarGallery').style.display = 'none';
}
</script>

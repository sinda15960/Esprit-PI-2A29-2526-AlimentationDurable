<div class="auth-container">
    <div class="auth-card login-card-simple">
        <div class="auth-right">
            <div class="auth-right-content">
                <div class="logo-mini">
                    <span class="logo-icon">🥗</span>
                    <span>NutriFlow AI</span>
                </div>
                <h3>Welcome back!</h3>
                <p class="auth-subtitle">Log in to access your account</p>

                <!-- AI Features Section -->
                <div class="ai-features">
                    <div class="voice-section">
                        <button type="button" class="voice-btn" id="voiceBtn" onclick="startVoiceRecognition()">
                            <span>🎤</span> Voice to Text
                        </button>
                        <span class="voice-status" id="voiceStatus">Click the mic to speak your email</span>
                    </div>
                    <div class="face-section">
                        <button type="button" class="face-btn" id="faceBtn" onclick="startFaceLogin()">
                            <span>😀</span> Face Login
                        </button>
                        <div class="face-camera" id="faceCamera" style="display: none;">
                            <div class="camera-container">
                                <video id="faceVideo" class="face-video" autoplay muted playsinline></video>
                                <canvas id="faceCanvas" class="face-canvas"></canvas>
                                <div class="face-status" id="faceStatus">Initializing camera...</div>
                                <div class="face-progress">
                                    <div class="face-progress-bar" id="faceProgressBar"></div>
                                </div>
                                <button class="face-cancel-btn" onclick="stopFaceLogin()">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Strength Game Section -->
                <div class="password-strength-container">
                    <div class="strength-bars">
                        <div class="strength-bar" id="bar1"></div>
                        <div class="strength-bar" id="bar2"></div>
                        <div class="strength-bar" id="bar3"></div>
                        <div class="strength-bar" id="bar4"></div>
                    </div>
                    <div class="strength-feedback" id="strengthFeedback">
                        <span class="strength-icon" id="strengthIcon">🔒</span>
                        <span class="strength-text" id="strengthText">Enter a password</span>
                    </div>
                    <div class="strength-tips" id="strengthTips">
                        <div class="tip-item" id="tipLength">📏 At least 8 characters</div>
                        <div class="tip-item" id="tipUpper">🔠 Uppercase letter</div>
                        <div class="tip-item" id="tipNumber">🔢 Number</div>
                        <div class="tip-item" id="tipSpecial">✨ Special character (!@#$%)</div>
                    </div>
                </div>

                <?php if(isset($_SESSION['errors'])): ?>
                    <div class="error-messages">
                        <?php foreach($_SESSION['errors'] as $error): ?>
                            <div class="error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <!-- Account Disabled Message -->
                <?php if(isset($_SESSION['account_disabled'])): ?>
                    <div class="error-message-disabled">
                        <div class="disabled-icon">⚠️</div>
                        <div class="disabled-content">
                            <strong>Account Disabled</strong>
                            <p><?php echo $_SESSION['account_disabled']; unset($_SESSION['account_disabled']); ?></p>
                            <a href="javascript:void(0)" class="contact-admin-btn" onclick="openContactModal()">Contact Administrator</a>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=login" onsubmit="return validateLoginForm()">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="example@nutriflow.com" value="<?php echo htmlspecialchars($_SESSION['old']['email'] ?? ''); ?>">
                        <div class="error-text" id="emailError"></div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                        <div class="error-text" id="passwordError"></div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember_me" value="1">
                            <span>Remember me</span>
                        </label>
                        <a href="index.php?action=forgot_password" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="auth-btn" id="loginBtn">Log In</button>
                </form>

                <div class="divider">
                    <span>or continue with</span>
                </div>

                <div class="social-login">
                    <button class="social-btn google" onclick="openSocialLogin('google')">
                        <span>G</span> Google
                    </button>
                    <button class="social-btn apple" onclick="openSocialLogin('apple')">
                        <span>🍎</span> Apple
                    </button>
                    <button class="social-btn discord" onclick="openSocialLogin('discord')">
                        <span>💬</span> Discord
                    </button>
                </div>

                <div class="auth-footer">
                    <p>New here? <a href="index.php?action=register">Create account</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Social Login Simulation -->
<div id="socialLoginModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header" id="socialModalHeader">
            <span class="modal-close" onclick="closeSocialModal()">&times;</span>
            <h2 id="socialModalTitle">Login with Google</h2>
        </div>
        <div class="modal-body" style="text-align: left;">
            <div id="socialLoading" style="text-align: center; display: none;">
                <div class="spinner"></div>
                <p style="margin-top: 1rem;">Redirecting to <span id="loadingProvider">Google</span>...</p>
            </div>
            <div id="socialForm">
                <div class="social-info" style="text-align: center; margin-bottom: 1.5rem;">
                    <span id="socialIcon" style="font-size: 3rem;">🌐</span>
                    <p id="socialMessage" style="margin-top: 0.5rem; color: #718096;">Connect with your existing account or create a new one</p>
                </div>
                <div class="form-group">
                    <label for="social_email">Email Address</label>
                    <input type="text" id="social_email" placeholder="your@email.com">
                    <div class="error-text" id="socialEmailError"></div>
                </div>
                <div class="form-group">
                    <label for="social_username">Username (optional)</label>
                    <input type="text" id="social_username" placeholder="Your name">
                </div>
                <div class="form-options" style="justify-content: center;">
                    <button class="auth-btn" id="socialConnectBtn" onclick="processSocialLogin()" style="width: auto; padding: 0.75rem 2rem;">
                        Connect with <span id="socialBtnName">Google</span> →
                    </button>
                </div>
                <div class="divider" style="margin: 1.5rem 0;">
                    <span>or</span>
                </div>
                <div style="text-align: center;">
                    <button class="btn-feature" onclick="closeSocialModal()" style="background: #e2e8f0; padding: 0.6rem 1.5rem; border: none; border-radius: 10px; cursor: pointer;">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal after social login -->
<div id="socialSuccessModal" class="modal">
    <div class="modal-content" style="max-width: 380px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #14532d);">
            <span class="modal-close" onclick="closeSuccessModal()">&times;</span>
            <h2 style="text-align: center;">🎉 Welcome!</h2>
        </div>
        <div class="modal-body" style="text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 1rem;" id="successIcon">✅</div>
            <h3 id="successTitle">Successfully Connected!</h3>
            <p id="successMessage" style="color: #718096; margin: 1rem 0;">You are now logged in with your Google account.</p>
            <button class="auth-btn" onclick="redirectAfterSocialLogin()" style="width: 100%;">Continue to Dashboard →</button>
        </div>
    </div>
</div>

<!-- Contact Admin Modal -->
<div id="contactModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
            <span class="modal-close" onclick="closeContactModal()">&times;</span>
            <h2>📧 Contact Administrator</h2>
        </div>
        <div class="modal-body" style="text-align: left;">
            <div id="contactSuccess" style="display: none; background: #dcfce7; color: #166534; padding: 0.75rem; border-radius: 10px; margin-bottom: 1rem;">
                ✅ Your message has been sent! The administrator will contact you soon.
            </div>
            <div id="contactError" style="display: none; background: #fee2e2; color: #dc2626; padding: 0.75rem; border-radius: 10px; margin-bottom: 1rem;">
                ❌ Failed to send message. Please try again.
            </div>
            <form id="contactForm" onsubmit="sendContactMessage(event)">
                <div class="form-group">
                    <label for="contact_name">Your Name</label>
                    <input type="text" id="contact_name" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="contact_email">Your Email</label>
                    <input type="email" id="contact_email" placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label for="contact_message">Message</label>
                    <textarea id="contact_message" rows="4" style="width:100%; padding:0.75rem; border-radius:10px; border:2px solid #e2e8f0;" placeholder="My account was disabled. Please help me reactivate it." required></textarea>
                </div>
                <div class="form-options" style="justify-content: center; margin-top: 1rem; gap: 0.5rem;">
                    <button type="submit" class="auth-btn" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">Send Message</button>
                    <button type="button" class="btn-cancel" onclick="closeContactModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* AI Features Section */
.ai-features {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.voice-section, .face-section {
    flex: 1;
    text-align: center;
}

.voice-btn, .face-btn {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    border: none;
    padding: 0.6rem 1rem;
    border-radius: 50px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    width: 100%;
    justify-content: center;
}

.voice-btn:hover, .face-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(139, 92, 246, 0.4);
}

.voice-btn.listening {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    animation: pulse 1.5s infinite;
}

.face-btn.scanning {
    background: linear-gradient(135deg, #16a34a, #14532d);
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

.voice-status {
    display: block;
    font-size: 0.7rem;
    color: #718096;
    margin-top: 0.5rem;
}

/* Face Camera Styles */
.face-camera {
    margin-top: 1rem;
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    border-radius: 20px;
    padding: 1rem;
    animation: fadeIn 0.3s ease;
}

.camera-container {
    position: relative;
    text-align: center;
}

.face-video {
    width: 100%;
    max-width: 300px;
    border-radius: 16px;
    transform: scaleX(-1);
    background: #000;
}

.face-canvas {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    max-width: 300px;
    border-radius: 16px;
    pointer-events: none;
}

.face-status {
    color: white;
    font-size: 0.8rem;
    margin: 0.75rem 0;
}

.face-progress {
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
    height: 4px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.face-progress-bar {
    width: 0%;
    height: 100%;
    background: linear-gradient(90deg, #16a34a, #22c55e);
    border-radius: 10px;
    transition: width 0.05s linear;
}

.face-cancel-btn {
    background: rgba(255,255,255,0.2);
    border: none;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    color: white;
    cursor: pointer;
    font-size: 0.7rem;
    margin-top: 0.5rem;
    transition: all 0.3s;
}

.face-cancel-btn:hover {
    background: rgba(255,255,255,0.3);
}

/* Password Strength Game Styles */
.password-strength-container {
    margin-bottom: 1.5rem;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.strength-bars {
    display: flex;
    gap: 6px;
    margin-bottom: 10px;
}

.strength-bar {
    height: 6px;
    flex: 1;
    background: #e2e8f0;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.strength-bar.weak {
    background: #ef4444;
}
.strength-bar.fair {
    background: #f59e0b;
}
.strength-bar.good {
    background: #8b5cf6;
}
.strength-bar.strong {
    background: #10b981;
}

.strength-feedback {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}

.strength-icon {
    font-size: 1.2rem;
}

.strength-text {
    font-size: 0.8rem;
    font-weight: 500;
    color: #4a5568;
}

.strength-tips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}

.tip-item {
    font-size: 0.7rem;
    padding: 3px 8px;
    background: white;
    border-radius: 20px;
    color: #718096;
    transition: all 0.3s ease;
}

.tip-item.completed {
    background: #dcfce7;
    color: #166534;
    text-decoration: line-through;
    opacity: 0.7;
}

/* Account Disabled Message */
.error-message-disabled {
    background: #fee2e2;
    border-left: 4px solid #dc2626;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.disabled-icon {
    font-size: 2rem;
}

.disabled-content strong {
    display: block;
    color: #991b1b;
    margin-bottom: 0.25rem;
}

.disabled-content p {
    color: #7f1d1d;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.contact-admin-btn {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #dc2626;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.75rem;
    transition: all 0.3s;
    cursor: pointer;
    border: none;
}

.contact-admin-btn:hover {
    background: #b91c1c;
}

/* Social Login Modal Styles */
.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e2e8f0;
    border-top-color: #16a34a;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
.social-info {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 16px;
}
.btn-feature {
    cursor: pointer;
    transition: all 0.3s;
}
.btn-feature:hover {
    background: #cbd5e0 !important;
    transform: translateY(-2px);
}

/* Error messages */
.error-messages {
    margin-bottom: 1rem;
}
.error {
    background: #fee2e2;
    color: #dc2626;
    padding: 0.5rem;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
}
.success-message {
    background: #dcfce7;
    color: #166534;
    padding: 0.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}
.error-text {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.btn-cancel {
    background: #e2e8f0;
    color: #4a5568;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
}
.btn-cancel:hover {
    background: #cbd5e0;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
// ========== FACE LOGIN WITH RECOGNITION ==========
let faceVideo = null;
let faceCanvas = null;
let faceStream = null;
let faceDetectionInterval = null;
let capturedFrames = [];
let isCapturing = false;

async function startFaceLogin() {
    const faceCamera = document.getElementById('faceCamera');
    const faceBtn = document.getElementById('faceBtn');
    const faceStatus = document.getElementById('faceStatus');
    const faceProgressBar = document.getElementById('faceProgressBar');
    
    faceCamera.style.display = 'block';
    faceBtn.disabled = true;
    faceBtn.classList.add('scanning');
    faceStatus.innerHTML = '📷 Requesting camera access...';
    faceProgressBar.style.width = '10%';
    capturedFrames = [];
    isCapturing = true;
    
    try {
        faceVideo = document.getElementById('faceVideo');
        faceCanvas = document.getElementById('faceCanvas');
        
        faceStream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 320 },
                height: { ideal: 240 },
                facingMode: 'user'
            } 
        });
        
        faceVideo.srcObject = faceStream;
        
        faceVideo.onloadedmetadata = () => {
            faceVideo.play();
            faceStatus.innerHTML = '🎥 Camera ready! Look at the camera...';
            faceProgressBar.style.width = '30%';
            
            setTimeout(() => {
                startFaceCapture();
            }, 1000);
        };
        
    } catch(error) {
        console.error('Camera error:', error);
        faceStatus.innerHTML = '❌ Camera access denied. Please check permissions.';
        faceProgressBar.style.width = '0%';
        faceBtn.disabled = false;
        faceBtn.classList.remove('scanning');
        
        setTimeout(() => {
            faceCamera.style.display = 'none';
        }, 3000);
    }
}

function startFaceCapture() {
    let captureCount = 0;
    const requiredCaptures = 20;
    
    const drawFrame = () => {
        if(!faceVideo || faceVideo.paused || faceVideo.ended || !isCapturing) return;
        
        const displayCtx = faceCanvas.getContext('2d');
        faceCanvas.width = faceVideo.videoWidth;
        faceCanvas.height = faceVideo.videoHeight;
        displayCtx.clearRect(0, 0, faceCanvas.width, faceCanvas.height);
        
        const centerX = faceCanvas.width / 2;
        const centerY = faceCanvas.height / 2;
        const boxWidth = faceCanvas.width * 0.5;
        const boxHeight = faceCanvas.height * 0.6;
        
        displayCtx.strokeStyle = '#16a34a';
        displayCtx.lineWidth = 3;
        displayCtx.strokeRect(centerX - boxWidth/2, centerY - boxHeight/2, boxWidth, boxHeight);
        
        const dotSize = 5;
        displayCtx.fillStyle = '#16a34a';
        displayCtx.fillRect(centerX - boxWidth/2 - 2, centerY - boxHeight/2 - 2, dotSize, dotSize);
        displayCtx.fillRect(centerX + boxWidth/2 - 2, centerY - boxHeight/2 - 2, dotSize, dotSize);
        displayCtx.fillRect(centerX - boxWidth/2 - 2, centerY + boxHeight/2 - 2, dotSize, dotSize);
        displayCtx.fillRect(centerX + boxWidth/2 - 2, centerY + boxHeight/2 - 2, dotSize, dotSize);
        
        const scanLine = (Date.now() / 30) % (boxHeight + 20);
        displayCtx.beginPath();
        displayCtx.moveTo(centerX - boxWidth/2, centerY - boxHeight/2 + scanLine);
        displayCtx.lineTo(centerX + boxWidth/2, centerY - boxHeight/2 + scanLine);
        displayCtx.strokeStyle = '#22c55e';
        displayCtx.lineWidth = 2;
        displayCtx.stroke();
    };
    
    const captureInterval = setInterval(() => {
        if(!isCapturing) {
            clearInterval(captureInterval);
            return;
        }
        
        if(captureCount < requiredCaptures && faceVideo && faceVideo.readyState === 4) {
            const canvas = document.createElement('canvas');
            canvas.width = faceVideo.videoWidth;
            canvas.height = faceVideo.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(faceVideo, 0, 0, canvas.width, canvas.height);
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const signature = compressFrame(imageData);
            capturedFrames.push(signature);
            
            captureCount++;
            const progress = 30 + (captureCount / requiredCaptures) * 60;
            document.getElementById('faceProgressBar').style.width = Math.min(90, progress) + '%';
            document.getElementById('faceStatus').innerHTML = `📸 Capturing face (${captureCount}/${requiredCaptures})...`;
            
            if(captureCount >= requiredCaptures) {
                clearInterval(captureInterval);
                document.getElementById('faceProgressBar').style.width = '95%';
                document.getElementById('faceStatus').innerHTML = '🔄 Recognizing...';
                
                const masterSignature = capturedFrames.join('|');
                recognizeFace(masterSignature);
            }
        }
        
        drawFrame();
    }, 200);
    
    function animateFrame() {
        if(isCapturing && faceVideo && faceVideo.readyState === 4) {
            drawFrame();
        }
        requestAnimationFrame(animateFrame);
    }
    animateFrame();
}

function compressFrame(imageData) {
    let signature = '';
    const step = Math.floor(imageData.data.length / 100);
    for(let i = 0; i < imageData.data.length; i += step) {
        signature += imageData.data[i] + ',' + imageData.data[i+1] + ',' + imageData.data[i+2] + ';';
    }
    return signature;
}

async function recognizeFace(signature) {
    const faceStatus = document.getElementById('faceStatus');
    const faceProgressBar = document.getElementById('faceProgressBar');
    
    try {
        const response = await fetch('index.php?action=login_with_face', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ face_signature: signature })
        });
        
        const data = await response.json();
        
        console.log('Face recognition response:', data);
        
        if(data.success) {
            faceProgressBar.style.width = '100%';
            faceStatus.innerHTML = '🎉 Face recognized! Logging in...';
            
            setTimeout(() => {
                window.location.href = 'index.php?action=profile';
            }, 1000);
        } else {
            faceStatus.innerHTML = '❌ ' + (data.message || 'Face not recognized. Please use email login.');
            faceProgressBar.style.width = '0%';
            
            setTimeout(() => {
                stopFaceLogin();
            }, 2000);
        }
    } catch(error) {
        console.error('Recognition error:', error);
        faceStatus.innerHTML = '❌ Recognition failed. Please try again.';
        
        setTimeout(() => {
            stopFaceLogin();
        }, 2000);
    }
}

function stopFaceLogin() {
    isCapturing = false;
    
    if(faceStream) {
        faceStream.getTracks().forEach(track => track.stop());
        faceStream = null;
    }
    
    if(faceCanvas) {
        const ctx = faceCanvas.getContext('2d');
        ctx.clearRect(0, 0, faceCanvas.width, faceCanvas.height);
    }
    
    const faceCamera = document.getElementById('faceCamera');
    const faceBtn = document.getElementById('faceBtn');
    
    if(faceCamera) faceCamera.style.display = 'none';
    if(faceBtn) {
        faceBtn.disabled = false;
        faceBtn.classList.remove('scanning');
    }
    
    const faceProgressBar = document.getElementById('faceProgressBar');
    if(faceProgressBar) faceProgressBar.style.width = '0%';
}

// ========== VOICE TO TEXT ==========
let recognition = null;
let isListening = false;

function startVoiceRecognition() {
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        alert('Sorry, your browser does not support speech recognition. Please try Chrome or Edge.');
        return;
    }
    
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.lang = 'en-US';
    
    const voiceBtn = document.getElementById('voiceBtn');
    const voiceStatus = document.getElementById('voiceStatus');
    const emailInput = document.getElementById('email');
    
    recognition.onstart = function() {
        isListening = true;
        voiceBtn.classList.add('listening');
        voiceStatus.innerHTML = '🎤 Listening... Speak your email address';
        voiceStatus.style.color = '#ef4444';
    };
    
    recognition.onresult = function(event) {
        const transcript = event.results[0][0].transcript;
        const cleanedEmail = transcript.toLowerCase().replace(/\s/g, '').replace(/dot/g, '.').replace(/at/g, '@');
        
        if(emailInput) {
            emailInput.value = cleanedEmail;
            const inputEvent = new Event('input', { bubbles: true });
            emailInput.dispatchEvent(inputEvent);
        }
        
        voiceStatus.innerHTML = `✅ Recognized: "${transcript}"`;
        voiceStatus.style.color = '#16a34a';
        
        setTimeout(() => {
            voiceStatus.innerHTML = 'Click the mic to speak your email';
            voiceStatus.style.color = '#718096';
        }, 3000);
    };
    
    recognition.onerror = function(event) {
        voiceStatus.innerHTML = '❌ Could not recognize. Please try again.';
        voiceStatus.style.color = '#dc2626';
        
        setTimeout(() => {
            voiceStatus.innerHTML = 'Click the mic to speak your email';
            voiceStatus.style.color = '#718096';
        }, 3000);
    };
    
    recognition.onend = function() {
        isListening = false;
        voiceBtn.classList.remove('listening');
    };
    
    recognition.start();
}

// ========== PASSWORD STRENGTH GAME ==========
const passwordInput = document.getElementById('password');
const bars = {
    bar1: document.getElementById('bar1'),
    bar2: document.getElementById('bar2'),
    bar3: document.getElementById('bar3'),
    bar4: document.getElementById('bar4')
};
const strengthText = document.getElementById('strengthText');
const strengthIcon = document.getElementById('strengthIcon');
const tips = {
    length: document.getElementById('tipLength'),
    upper: document.getElementById('tipUpper'),
    number: document.getElementById('tipNumber'),
    special: document.getElementById('tipSpecial')
};

function checkPasswordStrength(password) {
    let score = 0;
    let checks = {
        length: false,
        upper: false,
        number: false,
        special: false
    };
    
    if(password.length >= 8) {
        score++;
        checks.length = true;
    }
    
    if(/[A-Z]/.test(password)) {
        score++;
        checks.upper = true;
    }
    
    if(/[0-9]/.test(password)) {
        score++;
        checks.number = true;
    }
    
    if(/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        score++;
        checks.special = true;
    }
    
    const barKeys = ['bar1', 'bar2', 'bar3', 'bar4'];
    for(let i = 0; i < barKeys.length; i++) {
        if(i < score) {
            if(score === 1) bars[barKeys[i]].className = 'strength-bar weak';
            else if(score === 2) bars[barKeys[i]].className = 'strength-bar fair';
            else if(score === 3) bars[barKeys[i]].className = 'strength-bar good';
            else if(score === 4) bars[barKeys[i]].className = 'strength-bar strong';
        } else {
            bars[barKeys[i]].className = 'strength-bar';
        }
    }
    
    if(password.length === 0) {
        strengthText.textContent = 'Enter a password';
        strengthIcon.textContent = '🔒';
    } else if(score === 1) {
        strengthText.textContent = 'Weak - Needs improvement';
        strengthIcon.textContent = '😟';
    } else if(score === 2) {
        strengthText.textContent = 'Fair - Getting there!';
        strengthIcon.textContent = '😐';
    } else if(score === 3) {
        strengthText.textContent = 'Good - Almost perfect!';
        strengthIcon.textContent = '😎';
    } else if(score === 4) {
        strengthText.textContent = 'Strong - Excellent password! 💪';
        strengthIcon.textContent = '🦸';
    }
    
    updateTip(tips.length, checks.length, 'At least 8 characters');
    updateTip(tips.upper, checks.upper, 'Uppercase letter');
    updateTip(tips.number, checks.number, 'Number');
    updateTip(tips.special, checks.special, 'Special character');
    
    return score;
}

function updateTip(element, isCompleted, text) {
    if(isCompleted) {
        element.classList.add('completed');
        element.innerHTML = `✅ ${text}`;
    } else {
        element.classList.remove('completed');
        element.innerHTML = `❌ ${text}`;
    }
}

if(passwordInput) {
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        if(password.length === 0) {
            const barsElements = document.querySelectorAll('.strength-bar');
            barsElements.forEach(bar => bar.className = 'strength-bar');
            strengthText.textContent = 'Enter a password';
            strengthIcon.textContent = '🔒';
            
            document.querySelectorAll('.tip-item').forEach(tip => {
                tip.classList.remove('completed');
                if(tip.id === 'tipLength') tip.innerHTML = '📏 At least 8 characters';
                else if(tip.id === 'tipUpper') tip.innerHTML = '🔠 Uppercase letter';
                else if(tip.id === 'tipNumber') tip.innerHTML = '🔢 Number';
                else if(tip.id === 'tipSpecial') tip.innerHTML = '✨ Special character (!@#$%)';
            });
        } else {
            checkPasswordStrength(password);
        }
    });
}

// ========== SOCIAL LOGIN FUNCTIONS ==========
let currentSocialProvider = '';

function openSocialLogin(provider) {
    currentSocialProvider = provider;
    
    const modalTitle = document.getElementById('socialModalTitle');
    const socialIcon = document.getElementById('socialIcon');
    const socialMessage = document.getElementById('socialMessage');
    const socialBtnName = document.getElementById('socialBtnName');
    const modalHeader = document.getElementById('socialModalHeader');
    const loadingProvider = document.getElementById('loadingProvider');
    
    if(provider === 'google') {
        modalTitle.innerHTML = 'Login with Google';
        socialIcon.innerHTML = '🔴🟡🟢🔵';
        socialMessage.innerHTML = 'Connect with your Google account or create a new one';
        socialBtnName.innerHTML = 'Google';
        modalHeader.style.background = 'linear-gradient(135deg, #db4437, #c5221f)';
        document.getElementById('social_email').placeholder = 'your@gmail.com';
        if(loadingProvider) loadingProvider.innerHTML = 'Google';
    } else if(provider === 'apple') {
        modalTitle.innerHTML = 'Login with Apple';
        socialIcon.innerHTML = '🍎';
        socialMessage.innerHTML = 'Connect with your Apple ID securely';
        socialBtnName.innerHTML = 'Apple';
        modalHeader.style.background = 'linear-gradient(135deg, #000000, #1a1a1a)';
        document.getElementById('social_email').placeholder = 'your@icloud.com';
        if(loadingProvider) loadingProvider.innerHTML = 'Apple';
    } else if(provider === 'discord') {
        modalTitle.innerHTML = 'Login with Discord';
        socialIcon.innerHTML = '💬🎮';
        socialMessage.innerHTML = 'Connect with your Discord account';
        socialBtnName.innerHTML = 'Discord';
        modalHeader.style.background = 'linear-gradient(135deg, #5865f2, #4752c4)';
        document.getElementById('social_email').placeholder = 'username@discord.com';
        if(loadingProvider) loadingProvider.innerHTML = 'Discord';
    }
    
    document.getElementById('social_email').value = '';
    document.getElementById('social_username').value = '';
    document.getElementById('socialEmailError').textContent = '';
    document.getElementById('socialForm').style.display = 'block';
    document.getElementById('socialLoading').style.display = 'none';
    
    document.getElementById('socialLoginModal').style.display = 'block';
}

function closeSocialModal() {
    document.getElementById('socialLoginModal').style.display = 'none';
}

function closeSuccessModal() {
    document.getElementById('socialSuccessModal').style.display = 'none';
}

function processSocialLogin() {
    const email = document.getElementById('social_email').value.trim();
    const username = document.getElementById('social_username').value.trim();
    const emailError = document.getElementById('socialEmailError');
    
    if(email === '') {
        emailError.textContent = 'Email address is required';
        return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(email)) {
        emailError.textContent = 'Please enter a valid email address';
        return;
    }
    
    emailError.textContent = '';
    
    document.getElementById('socialForm').style.display = 'none';
    document.getElementById('socialLoading').style.display = 'block';
    
    setTimeout(() => {
        closeSocialModal();
        showSocialSuccess(currentSocialProvider, email, username);
    }, 1500);
}

function showSocialSuccess(provider, email, username) {
    const successIcon = document.getElementById('successIcon');
    const successTitle = document.getElementById('successTitle');
    const successMessage = document.getElementById('successMessage');
    
    if(provider === 'google') {
        successIcon.innerHTML = '🔴🟡🟢🔵';
        successTitle.innerHTML = 'Welcome Google User!';
        successMessage.innerHTML = `You are now logged in with<br><strong>${email}</strong><br>Your Google account is connected!`;
    } else if(provider === 'apple') {
        successIcon.innerHTML = '🍎';
        successTitle.innerHTML = 'Welcome Apple User!';
        successMessage.innerHTML = `You are now logged in with<br><strong>${email}</strong><br>Your Apple ID is connected!`;
    } else if(provider === 'discord') {
        successIcon.innerHTML = '💬🎮';
        successTitle.innerHTML = 'Welcome Discord User!';
        successMessage.innerHTML = `You are now logged in with<br><strong>${username || email}</strong><br>Your Discord account is connected!`;
    }
    
    simulateSocialLogin(email, username);
    
    document.getElementById('socialSuccessModal').style.display = 'block';
}

function simulateSocialLogin(email, username) {
    fetch('index.php?action=social_login_ajax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email, username: username, provider: currentSocialProvider })
    }).catch(() => {
        console.log('Social login simulated');
    });
}

function redirectAfterSocialLogin() {
    document.getElementById('socialSuccessModal').style.display = 'none';
    window.location.href = 'index.php?action=profile';
}

// ========== CONTACT MODAL FUNCTIONS ==========
function openContactModal() {
    document.getElementById('contactModal').style.display = 'block';
    document.getElementById('contactSuccess').style.display = 'none';
    document.getElementById('contactError').style.display = 'none';
    document.getElementById('contactForm').reset();
    document.getElementById('contactForm').style.display = 'block';
}

function closeContactModal() {
    document.getElementById('contactModal').style.display = 'none';
}

function sendContactMessage(event) {
    event.preventDefault();
    
    const name = document.getElementById('contact_name').value;
    const email = document.getElementById('contact_email').value;
    const message = document.getElementById('contact_message').value;
    
    const submitBtn = document.querySelector('#contactForm button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Sending...';
    submitBtn.disabled = true;
    
    fetch('index.php?action=send_contact_message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        if(data.success) {
            document.getElementById('contactForm').style.display = 'none';
            document.getElementById('contactSuccess').style.display = 'block';
            
            setTimeout(() => {
                closeContactModal();
                document.getElementById('contactForm').style.display = 'block';
            }, 3000);
        } else {
            document.getElementById('contactError').style.display = 'block';
            setTimeout(() => {
                document.getElementById('contactError').style.display = 'none';
            }, 3000);
        }
    })
    .catch(error => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        document.getElementById('contactError').style.display = 'block';
        setTimeout(() => {
            document.getElementById('contactError').style.display = 'none';
        }, 3000);
    });
}

// ========== CLOSE MODALS WHEN CLICKING OUTSIDE ==========
window.onclick = function(event) {
    const socialModal = document.getElementById('socialLoginModal');
    const successModal = document.getElementById('socialSuccessModal');
    const contactModal = document.getElementById('contactModal');
    if (event.target == socialModal) closeSocialModal();
    if (event.target == successModal) closeSuccessModal();
    if (event.target == contactModal) closeContactModal();
}

// ========== LOGIN FORM VALIDATION ==========
function validateLoginForm() {
    let isValid = true;
    
    const email = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email && !emailRegex.test(email.value)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address';
        isValid = false;
    } else {
        document.getElementById('emailError').textContent = '';
    }
    
    const password = document.getElementById('password');
    if(password && password.value.length === 0) {
        document.getElementById('passwordError').textContent = 'Password is required';
        isValid = false;
    } else {
        document.getElementById('passwordError').textContent = '';
    }
    
    return isValid;
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if(faceStream) {
        faceStream.getTracks().forEach(track => track.stop());
    }
});
</script>

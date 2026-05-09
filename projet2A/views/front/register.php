<div class="auth-container">
    <div class="auth-card large">
        <div class="auth-header">
            <div class="logo-mini">🥗 NutriFlow AI</div>
            <h2>Create Account</h2>
            <p>Start your sustainable nutrition journey</p>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <script>
                sessionStorage.setItem('justRegistered', 'true');
            </script>
        <?php endif; ?>

        <?php if(isset($_SESSION['errors'])): ?>
            <div class="error-messages">
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Password Strength Game -->
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

        <form method="POST" action="index.php?action=register" onsubmit="return validateRegisterForm()" id="registerForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['old']['username'] ?? ''); ?>">
                    <div class="error-text" id="usernameError"></div>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['old']['email'] ?? ''); ?>">
                    <div class="error-text" id="emailError"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password">
                    <div class="error-text" id="passwordError"></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                    <div class="error-text" id="confirmPasswordError"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_SESSION['old']['full_name'] ?? ''); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['old']['phone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($_SESSION['old']['age'] ?? ''); ?>">
                    <div class="error-text" id="ageError"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="number" step="0.1" id="weight" name="weight" value="<?php echo htmlspecialchars($_SESSION['old']['weight'] ?? ''); ?>">
                    <div class="error-text" id="weightError"></div>
                </div>

                <div class="form-group">
                    <label for="height">Height (cm)</label>
                    <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($_SESSION['old']['height'] ?? ''); ?>">
                    <div class="error-text" id="heightError"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="dietary_preference">Dietary Preference</label>
                <select id="dietary_preference" name="dietary_preference">
                    <option value="">Select...</option>
                    <option value="omnivore" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'omnivore') ? 'selected' : ''; ?>>Omnivore</option>
                    <option value="vegetarian" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'vegetarian') ? 'selected' : ''; ?>>Vegetarian</option>
                    <option value="vegan" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'vegan') ? 'selected' : ''; ?>>Vegan</option>
                    <option value="pescatarian" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'pescatarian') ? 'selected' : ''; ?>>Pescatarian</option>
                    <option value="keto" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'keto') ? 'selected' : ''; ?>>Keto</option>
                </select>
            </div>

            <button type="submit" class="auth-btn">Create Account</button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="index.php?action=login">Login</a></p>
        </div>
    </div>
</div>

<!-- Face ID Enrollment Modal -->
<div id="faceEnrollModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
            <span class="modal-close" onclick="skipFaceEnrollment()">&times;</span>
            <h2>😀 Setup Face ID</h2>
        </div>
        <div class="modal-body" style="text-align: center;">
            <p>Would you like to enable Face ID for faster login next time?</p>
            <div class="face-preview" id="facePreview" style="display: none;">
                <video id="enrollVideo" class="face-video-small" autoplay muted playsinline></video>
                <canvas id="enrollCanvas" class="face-canvas-small"></canvas>
                <div class="enroll-status" id="enrollStatus">Looking at camera...</div>
                <div class="enroll-progress">
                    <div class="enroll-progress-bar" id="enrollProgressBar"></div>
                </div>
            </div>
            <div class="modal-buttons" style="display: flex; gap: 1rem; justify-content: center; margin-top: 1rem;">
                <button class="btn-yes" onclick="startFaceEnrollment()">Yes, enable Face ID</button>
                <button class="btn-no" onclick="skipFaceEnrollment()">No, thanks</button>
            </div>
            <div class="enroll-actions" style="display: none; margin-top: 1rem;">
                <button class="btn-save-face" onclick="saveFaceSignature()">Save Face Signature</button>
                <button class="btn-skip" onclick="skipFaceEnrollment()">Skip</button>
            </div>
        </div>
    </div>
</div>

<style>
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

.face-video-small {
    width: 250px;
    height: 188px;
    border-radius: 16px;
    transform: scaleX(-1);
    margin: 0 auto;
    background: #000;
}

.face-canvas-small {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 250px;
    height: 188px;
    pointer-events: none;
}

.face-preview {
    position: relative;
    display: inline-block;
    margin: 1rem 0;
}

.enroll-status {
    font-size: 0.8rem;
    color: #718096;
    margin: 0.5rem 0;
}

.enroll-progress {
    width: 100%;
    height: 4px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 0.5rem;
}

.enroll-progress-bar {
    width: 0%;
    height: 100%;
    background: linear-gradient(90deg, #16a34a, #22c55e);
    border-radius: 10px;
    transition: width 0.1s linear;
}

.btn-yes, .btn-no, .btn-save-face, .btn-skip {
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-yes {
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
}

.btn-yes:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22,163,74,0.3);
}

.btn-no, .btn-skip {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-no:hover, .btn-skip:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

.btn-save-face {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
}

.btn-save-face:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(139,92,246,0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
}

.modal-close {
    font-size: 1.5rem;
    cursor: pointer;
    transition: opacity 0.3s;
}

.modal-close:hover {
    opacity: 0.7;
}

.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    width: 90%;
    max-width: 450px;
    border-radius: 20px;
    animation: slideDownModal 0.3s ease;
    overflow: hidden;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDownModal {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
let enrollStream = null;
let enrollVideo = null;
let enrollCanvas = null;
let faceSignatureData = null;
let enrollmentFrames = [];
let currentUserId = null;

function validateRegisterForm() {
    let isValid = true;
    
    clearErrors();
    
    const username = document.getElementById('username');
    if(username && username.value.length < 3) {
        showError('usernameError', 'Username must be at least 3 characters');
        isValid = false;
    }
    
    const email = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email && !emailRegex.test(email.value)) {
        showError('emailError', 'Please enter a valid email address');
        isValid = false;
    }
    
    const password = document.getElementById('password');
    if(password && password.value.length < 6) {
        showError('passwordError', 'Password must be at least 6 characters');
        isValid = false;
    }
    
    const confirmPassword = document.getElementById('confirm_password');
    if(password && confirmPassword && password.value !== confirmPassword.value) {
        showError('confirmPasswordError', 'Passwords do not match');
        isValid = false;
    }
    
    const age = document.getElementById('age');
    if(age && age.value && (age.value < 1 || age.value > 120)) {
        showError('ageError', 'Age must be between 1 and 120');
        isValid = false;
    }
    
    const weight = document.getElementById('weight');
    if(weight && weight.value && (weight.value < 20 || weight.value > 300)) {
        showError('weightError', 'Weight must be between 20 and 300 kg');
        isValid = false;
    }
    
    const height = document.getElementById('height');
    if(height && height.value && (height.value < 100 || height.value > 250)) {
        showError('heightError', 'Height must be between 100 and 250 cm');
        isValid = false;
    }
    
    return isValid;
}

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if(errorElement) {
        errorElement.textContent = message;
    }
}

function clearErrors() {
    const errorElements = document.querySelectorAll('.error-text');
    errorElements.forEach(element => {
        element.textContent = '';
    });
}

// Password Strength
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

// Face ID after registration
<?php if(isset($_SESSION['new_user_id']) && $_SESSION['new_user_id']): ?>
    currentUserId = <?php echo $_SESSION['new_user_id']; ?>;
    <?php unset($_SESSION['new_user_id']); ?>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            showFaceEnrollmentModal();
        }, 500);
    });
<?php endif; ?>

function showFaceEnrollmentModal() {
    document.getElementById('faceEnrollModal').style.display = 'block';
}

function startFaceEnrollment() {
    document.querySelector('#faceEnrollModal .modal-buttons').style.display = 'none';
    document.getElementById('facePreview').style.display = 'inline-block';
    document.querySelector('.enroll-actions').style.display = 'block';
    startEnrollmentCamera();
}

async function startEnrollmentCamera() {
    const enrollStatus = document.getElementById('enrollStatus');
    const enrollProgressBar = document.getElementById('enrollProgressBar');
    
    enrollStatus.innerHTML = '📷 Requesting camera access...';
    enrollProgressBar.style.width = '10%';
    
    try {
        enrollVideo = document.getElementById('enrollVideo');
        enrollCanvas = document.getElementById('enrollCanvas');
        
        enrollStream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 320 },
                height: { ideal: 240 },
                facingMode: 'user'
            } 
        });
        
        enrollVideo.srcObject = enrollStream;
        await enrollVideo.play();
        
        enrollStatus.innerHTML = '🎥 Look at the camera and move slightly...';
        enrollProgressBar.style.width = '30%';
        
        captureFaceSignatures();
        
    } catch(error) {
        console.error('Camera error:', error);
        enrollStatus.innerHTML = '❌ Camera access denied. Face ID disabled.';
        enrollProgressBar.style.width = '0%';
    }
}

function captureFaceSignatures() {
    enrollmentFrames = [];
    let captureCount = 0;
    const requiredCaptures = 20;
    let lastFrame = null;
    let motionDetected = false;
    
    const captureInterval = setInterval(() => {
        if(!enrollVideo || enrollVideo.paused || enrollVideo.ended || enrollVideo.readyState !== 4) return;
        
        const canvas = document.createElement('canvas');
        canvas.width = enrollVideo.videoWidth;
        canvas.height = enrollVideo.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(enrollVideo, 0, 0, canvas.width, canvas.height);
        
        const displayCtx = enrollCanvas.getContext('2d');
        enrollCanvas.width = enrollVideo.videoWidth;
        enrollCanvas.height = enrollVideo.videoHeight;
        displayCtx.clearRect(0, 0, enrollCanvas.width, enrollCanvas.height);
        
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        
        if(lastFrame) {
            let diff = 0;
            for(let i = 0; i < imageData.data.length; i += 100) {
                const rDiff = Math.abs(imageData.data[i] - lastFrame.data[i]);
                const gDiff = Math.abs(imageData.data[i+1] - lastFrame.data[i+1]);
                const bDiff = Math.abs(imageData.data[i+2] - lastFrame.data[i+2]);
                diff += (rDiff + gDiff + bDiff);
            }
            
            const avgDiff = diff / 100;
            
            if(avgDiff > 20 && avgDiff < 200) {
                motionDetected = true;
                
                const centerX = enrollCanvas.width / 2;
                const centerY = enrollCanvas.height / 2;
                const boxWidth = enrollCanvas.width * 0.5;
                const boxHeight = enrollCanvas.height * 0.6;
                
                displayCtx.strokeStyle = '#16a34a';
                displayCtx.lineWidth = 3;
                displayCtx.strokeRect(centerX - boxWidth/2, centerY - boxHeight/2, boxWidth, boxHeight);
                
                const signature = compressImageData(imageData);
                enrollmentFrames.push(signature);
                captureCount++;
                
                const progress = Math.min(90, 30 + (captureCount / requiredCaptures) * 60);
                document.getElementById('enrollProgressBar').style.width = progress + '%';
                document.getElementById('enrollStatus').innerHTML = `✅ Capturing face (${captureCount}/${requiredCaptures})...`;
                
                if(captureCount >= requiredCaptures) {
                    clearInterval(captureInterval);
                    faceSignatureData = createMasterSignature(enrollmentFrames);
                    document.getElementById('enrollProgressBar').style.width = '100%';
                    document.getElementById('enrollStatus').innerHTML = '🎉 Face captured successfully! Click "Save Face Signature" to complete.';
                }
            } else if(motionDetected) {
                document.getElementById('enrollStatus').innerHTML = '😕 Keep looking at the camera...';
            }
        }
        
        lastFrame = imageData;
    }, 200);
}

function compressImageData(imageData) {
    let signature = '';
    for(let i = 0; i < Math.min(imageData.data.length, 5000); i += 100) {
        signature += imageData.data[i] + ',' + imageData.data[i+1] + ',' + imageData.data[i+2] + ';';
    }
    return signature;
}

function createMasterSignature(frames) {
    return frames.join('|');
}

function saveFaceSignature() {
    if(!faceSignatureData) {
        alert('Please wait for face capture to complete.');
        return;
    }
    
    if(!currentUserId) {
        alert('User ID not found. Please login normally first.');
        skipFaceEnrollment();
        return;
    }
    
    fetch('index.php?action=save_face_signature', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            user_id: currentUserId, 
            face_signature: faceSignatureData 
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            closeFaceEnrollModal();
            alert('Face ID enabled successfully! You can now login with your face.');
            window.location.href = 'index.php?action=login';
        } else {
            alert('Failed to save face signature. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving face signature.');
    });
}

function skipFaceEnrollment() {
    closeFaceEnrollModal();
    window.location.href = 'index.php?action=login';
}

function closeFaceEnrollModal() {
    if(enrollStream) {
        enrollStream.getTracks().forEach(track => track.stop());
    }
    document.getElementById('faceEnrollModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('faceEnrollModal');
    if (event.target == modal) {
        skipFaceEnrollment();
    }
}
</script>

<?php unset($_SESSION['old']); ?>

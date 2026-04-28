<div class="auth-container">
    <div class="auth-card large">
        <div class="auth-header">
            <div class="logo-mini">🥗 NutriFlow AI</div>
            <h2>Create Account</h2>
            <p>Start your sustainable nutrition journey</p>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['errors'])): ?>
            <div class="error-messages">
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

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
/* Face ID Enrollment Styles */
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

/* Modal Header Close Button */
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
</style>

<script>
let enrollStream = null;
let enrollVideo = null;
let enrollCanvas = null;
let faceSignatureData = null;
let enrollmentFrames = [];
let currentUserId = null;

// Validation functions
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

// Intercept form submission to capture user ID after registration
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    // Let the form submit normally
    // The user ID will be available after PHP processes the registration
});

// Check if we should show Face ID modal (after successful registration)
<?php if(isset($_SESSION['new_user_id']) && $_SESSION['new_user_id']): ?>
    currentUserId = <?php echo $_SESSION['new_user_id']; ?>;
    <?php unset($_SESSION['new_user_id']); ?>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            showFaceEnrollmentModal();
        }, 500);
    });
<?php endif; ?>

// ========== FACE ID ENROLLMENT FUNCTIONS ==========

function showFaceEnrollmentModal() {
    document.getElementById('faceEnrollModal').style.display = 'block';
}

function startFaceEnrollment() {
    // Hide buttons, show camera
    document.querySelector('#faceEnrollModal .modal-buttons').style.display = 'none';
    document.getElementById('facePreview').style.display = 'inline-block';
    document.querySelector('.enroll-actions').style.display = 'block';
    
    startEnrollmentCamera();
}

async function startEnrollmentCamera() {
    const facePreview = document.getElementById('facePreview');
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
        
        // Start capturing face signatures
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
        
        // Capture current frame
        const canvas = document.createElement('canvas');
        canvas.width = enrollVideo.videoWidth;
        canvas.height = enrollVideo.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(enrollVideo, 0, 0, canvas.width, canvas.height);
        
        // Draw detection overlay
        const displayCtx = enrollCanvas.getContext('2d');
        enrollCanvas.width = enrollVideo.videoWidth;
        enrollCanvas.height = enrollVideo.videoHeight;
        displayCtx.clearRect(0, 0, enrollCanvas.width, enrollCanvas.height);
        
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        
        if(lastFrame) {
            // Calculate motion
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
                
                // Draw face rectangle
                const centerX = enrollCanvas.width / 2;
                const centerY = enrollCanvas.height / 2;
                const boxWidth = enrollCanvas.width * 0.5;
                const boxHeight = enrollCanvas.height * 0.6;
                
                displayCtx.strokeStyle = '#16a34a';
                displayCtx.lineWidth = 3;
                displayCtx.strokeRect(centerX - boxWidth/2, centerY - boxHeight/2, boxWidth, boxHeight);
                
                // Create a simple signature from pixel data
                const signature = compressImageData(imageData);
                enrollmentFrames.push(signature);
                captureCount++;
                
                const progress = Math.min(90, 30 + (captureCount / requiredCaptures) * 60);
                document.getElementById('enrollProgressBar').style.width = progress + '%';
                document.getElementById('enrollStatus').innerHTML = `✅ Capturing face (${captureCount}/${requiredCaptures})...`;
                
                if(captureCount >= requiredCaptures) {
                    clearInterval(captureInterval);
                    // Create master signature from all frames
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
    // Compress image data to create a signature
    let signature = '';
    for(let i = 0; i < Math.min(imageData.data.length, 5000); i += 100) {
        signature += imageData.data[i] + ',' + imageData.data[i+1] + ',' + imageData.data[i+2] + ';';
    }
    return signature;
}

function createMasterSignature(frames) {
    // Create a combined signature from all frames
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

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('faceEnrollModal');
    if (event.target == modal) {
        skipFaceEnrollment();
    }
}
</script>

<?php unset($_SESSION['old']); ?>

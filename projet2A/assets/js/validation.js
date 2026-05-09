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

function validateLoginForm() {
    let isValid = true;
    clearErrors();
    
    const email = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email && !emailRegex.test(email.value)) {
        showError('emailError', 'Please enter a valid email address');
        isValid = false;
    }
    
    const password = document.getElementById('password');
    if(password && password.value.length === 0) {
        showError('passwordError', 'Password is required');
        isValid = false;
    }
    
    return isValid;
}

function validateProfileForm() {
    let isValid = true;
    clearErrors();
    
    const username = document.getElementById('username');
    if(username && username.value.length < 3) {
        showError('usernameError', 'Username must be at least 3 characters');
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

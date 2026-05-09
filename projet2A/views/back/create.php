<?php 
$pageTitle = "Create Organization"; 
$pageSubtitle = "Add a new partner organization";
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #2d4a1e, #1a3a0f);">
            <h2 class="text-xl font-semibold text-white">New Organization</h2>
        </div>
        
        <form method="POST" action="/nutriflow-ai/public/admin/associations/store" class="p-6" id="associationForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name *</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <div id="error_name" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="text" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <div id="error_email" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="text" name="phone" id="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <div id="error_phone" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Tax ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax ID (SIRET) *</label>
                    <input type="text" name="siret" id="siret" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" maxlength="14">
                    <div id="error_siret" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <input type="text" name="address" id="address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <div id="error_address" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                    <input type="text" name="city" id="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <div id="error_city" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Postal Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                    <input type="text" name="postal_code" id="postal_code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" maxlength="5">
                    <div id="error_postal" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Mission -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mission / Description *</label>
                    <textarea name="mission" id="mission" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                    <div id="error_mission" class="text-red-500 text-sm mt-1 hidden"></div>
                    <p class="text-xs text-gray-500 mt-1">Minimum 20 characters</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="/nutriflow-ai/public/admin/associations" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 text-white rounded-lg transition" style="background: linear-gradient(135deg, #2d4a1e, #1a3a0f);">
                    <i class="fas fa-save mr-2"></i>
                    Create Organization
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('associationForm');
    
    function showError(fieldId, message) {
        const errorDiv = document.getElementById('error_' + fieldId);
        if(errorDiv) {
            errorDiv.innerHTML = message;
            errorDiv.classList.remove('hidden');
            const input = document.getElementById(fieldId);
            if(input) input.classList.add('border-red-500');
        }
    }
    
    function hideError(fieldId) {
        const errorDiv = document.getElementById('error_' + fieldId);
        if(errorDiv) {
            errorDiv.innerHTML = '';
            errorDiv.classList.add('hidden');
            const input = document.getElementById(fieldId);
            if(input) input.classList.remove('border-red-500');
        }
    }
    
    // Hide errors when user corrects
    const fields = ['name', 'email', 'phone', 'siret', 'address', 'city', 'postal_code', 'mission'];
    for(var i = 0; i < fields.length; i++) {
        const input = document.getElementById(fields[i]);
        if(input) {
            input.addEventListener('input', function() {
                hideError(this.id);
            });
        }
    }
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Hide all errors
        for(var i = 0; i < fields.length; i++) {
            hideError(fields[i]);
        }
        
        // Name validation
        const name = document.getElementById('name');
        if(!name.value || name.value.trim().length < 3) {
            showError('name', 'Name must contain at least 3 characters');
            isValid = false;
        }
        
        // Email validation
        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!email.value || !emailRegex.test(email.value)) {
            showError('email', 'Invalid email (example: name@domain.com)');
            isValid = false;
        }
        
        // Phone validation
        const phone = document.getElementById('phone');
        const phoneRegex = /^[0-9]{8}$/;
        if(!phone.value || !phoneRegex.test(phone.value)) {
            showError('phone', 'Invalid phone number (8 digits)');
            isValid = false;
        }
        
        // Tax ID validation
        const siret = document.getElementById('siret');
        const siretRegex = /^[0-9]{8}$/;
        if(!siret.value || !siretRegex.test(siret.value)) {
            showError('siret', 'Invalid Tax ID (8 digits)');
            isValid = false;
        }
        
        // Address validation
        const address = document.getElementById('address');
        if(!address.value || address.value.trim().length < 5) {
            showError('address', 'Invalid address (minimum 5 characters)');
            isValid = false;
        }
        
        // City validation
        const city = document.getElementById('city');
        if(!city.value || city.value.trim().length < 2) {
            showError('city', 'Invalid city name');
            isValid = false;
        }
        
        // Postal code validation
        const postalCode = document.getElementById('postal_code');
        const postalRegex = /^[0-9]{5}$/;
        if(!postalCode.value || !postalRegex.test(postalCode.value)) {
            showError('postal', 'Invalid postal code (5 digits)');
            isValid = false;
        }
        
        // Mission validation
        const mission = document.getElementById('mission');
        if(!mission.value || mission.value.trim().length < 20) {
            showError('mission', 'Mission must contain at least 20 characters');
            isValid = false;
        }
        
        if(!isValid) {
            e.preventDefault();
        }
    });
});
</script>

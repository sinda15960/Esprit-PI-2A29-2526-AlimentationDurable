<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Make a Donation</h1>
            <p class="text-green-100">Your generosity changes lives</p>
        </div>
        
        <form method="POST" action="/nutriflow-ai/public/don/store" id="donForm" class="p-8">
            <!-- Association -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Beneficiary Organization *</label>
                <select name="association_id" id="association_id" class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Select an organization</option>
                    <?php foreach($associations as $assoc): ?>
                    <option value="<?php echo $assoc['id']; ?>">
                        <?php echo htmlspecialchars($assoc['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div id="error_association" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <!-- Name and Email -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                    <input type="text" name="donor_name" id="donor_name" class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                    <div id="error_name" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input type="text" name="donor_email" id="donor_email" class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                    <div id="error_email" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
            </div>
            
            <!-- Donation Type -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Donation Type *</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-green-50">
                        <input type="radio" name="donation_type" value="monetary" class="mr-2"> Monetary
                    </label>
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-green-50">
                        <input type="radio" name="donation_type" value="food" class="mr-2"> Food
                    </label>
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-green-50">
                        <input type="radio" name="donation_type" value="equipment" class="mr-2"> Equipment
                    </label>
                </div>
                <div id="error_type" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <!-- Amount (monetary) -->
            <div id="monetaryFields" class="mb-6" style="display: none;">
                <label class="block text-gray-700 font-semibold mb-2">Amount (DT) *</label>
                <input type="number" name="amount" id="amount" step="0.01" class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                <div id="error_amount" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <!-- Food -->
            <div id="foodFields" class="mb-6" style="display: none;">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Food Type *</label>
                        <input type="text" name="food_type" id="food_type" class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                        <div id="error_foodtype" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Quantity (kg) *</label>
                        <input type="number" name="quantity" id="quantity" class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                        <div id="error_quantity" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>
            </div>
            
            <!-- Payment -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Payment Method *</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer">
                        <input type="radio" name="payment_method" value="card" class="mr-2"> Credit Card
                    </label>
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer">
                        <input type="radio" name="payment_method" value="paypal" class="mr-2"> PayPal
                    </label>
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer">
                        <input type="radio" name="payment_method" value="bank_transfer" class="mr-2"> Bank Transfer
                    </label>
                </div>
                <div id="error_payment" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <!-- Message -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Message (optional)</label>
                <textarea name="message" rows="4" class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-4 rounded-xl text-lg font-semibold hover:shadow-xl transition">
                <i class="fas fa-heart mr-2"></i>Donate Now
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic field management
    const radioButtons = document.querySelectorAll('input[name="donation_type"]');
    const monetaryFields = document.getElementById('monetaryFields');
    const foodFields = document.getElementById('foodFields');
    
    function toggleFields() {
        const selected = document.querySelector('input[name="donation_type"]:checked');
        if(selected) {
            if(selected.value === 'monetary') {
                monetaryFields.style.display = 'block';
                foodFields.style.display = 'none';
            } else if(selected.value === 'food') {
                monetaryFields.style.display = 'none';
                foodFields.style.display = 'block';
            } else {
                monetaryFields.style.display = 'none';
                foodFields.style.display = 'none';
            }
        }
    }
    
    for(var i = 0; i < radioButtons.length; i++) {
        radioButtons[i].addEventListener('change', toggleFields);
    }
    
    // Form validation
    const form = document.getElementById('donForm');
    
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
    const inputs = ['association_id', 'donor_name', 'donor_email', 'amount', 'food_type', 'quantity'];
    for(var i = 0; i < inputs.length; i++) {
        const input = document.getElementById(inputs[i]);
        if(input) {
            input.addEventListener('input', function() {
                const id = this.id;
                hideError(id);
                if(id === 'donor_name') hideError('name');
                if(id === 'donor_email') hideError('email');
                if(id === 'association_id') hideError('association');
            });
        }
    }
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Hide all errors
        const allErrors = document.querySelectorAll('[id^="error_"]');
        for(var i = 0; i < allErrors.length; i++) {
            allErrors[i].classList.add('hidden');
        }
        
        // Organization validation
        const association = document.getElementById('association_id');
        if(!association.value) {
            showError('association', 'Please select an organization');
            isValid = false;
        }
        
        // Name validation
        const donorName = document.getElementById('donor_name');
        if(!donorName.value || donorName.value.trim().length < 3) {
            showError('name', 'Name must contain at least 3 characters');
            isValid = false;
        }
        
        // Email validation
        const email = document.getElementById('donor_email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!email.value || !emailRegex.test(email.value)) {
            showError('email', 'Invalid email (example: name@domain.com)');
            isValid = false;
        }
        
        // Donation type validation
        const donationType = document.querySelector('input[name="donation_type"]:checked');
        if(!donationType) {
            showError('type', 'Please select a donation type');
            isValid = false;
        } else if(donationType.value === 'monetary') {
            const amount = document.getElementById('amount');
            if(!amount.value || amount.value <= 0 || amount.value > 100000) {
                showError('amount', 'Invalid amount (minimum 1 DT)');
                isValid = false;
            }
        } else if(donationType.value === 'food') {
            const foodType = document.getElementById('food_type');
            if(!foodType.value || foodType.value.trim().length < 2) {
                showError('foodtype', 'Invalid food type (minimum 2 characters)');
                isValid = false;
            }
            const quantity = document.getElementById('quantity');
            if(!quantity.value || quantity.value <= 0 || quantity.value > 10000) {
                showError('quantity', 'Invalid quantity (between 1kg and 10,000kg)');
                isValid = false;
            }
        }
        
        // Payment validation
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if(!paymentMethod) {
            showError('payment', 'Please select a payment method');
            isValid = false;
        }
        
        if(!isValid) {
            e.preventDefault();
        }
    });
});
</script>
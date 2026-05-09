<?php 
$pageTitle = "Add Donation"; 
$pageSubtitle = "Register a new donation";
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #2d4a1e, #1a3a0f);">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                New Donation
            </h2>
        </div>
        
        <form method="POST" action="/nutriflow-ai/public/admin/dons/store" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organization *</label>
                    <select name="association_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select an organization</option>
                        <?php foreach($associations as $assoc): ?>
                        <option value="<?php echo $assoc['id']; ?>"><?php echo htmlspecialchars($assoc['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Donor Name *</label>
                    <input type="text" name="donor_name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="text" name="donor_email" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="donor_phone" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Donation Type *</label>
                    <select name="donation_type" id="donation_type" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="monetary">Monetary</option>
                        <option value="food">Food</option>
                        <option value="equipment">Equipment</option>
                    </select>
                </div>
                
                <div id="monetaryField">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (DT)</label>
                    <input type="number" name="amount" step="0.01" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                
                <div id="foodField" style="display:none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Food Type</label>
                    <input type="text" name="food_type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                
                <div id="quantityField" style="display:none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity (kg)</label>
                    <input type="number" name="quantity" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="/nutriflow-ai/public/admin/dons" class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="text-white px-6 py-2 rounded-lg transition" style="background: linear-gradient(135deg, #2d4a1e, #1a3a0f);">
                    Create Donation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const donationType = document.getElementById('donation_type');
    const monetaryField = document.getElementById('monetaryField');
    const foodField = document.getElementById('foodField');
    const quantityField = document.getElementById('quantityField');
    
    donationType.addEventListener('change', function() {
        if(this.value === 'monetary') {
            monetaryField.style.display = 'block';
            foodField.style.display = 'none';
            quantityField.style.display = 'none';
        } else if(this.value === 'food') {
            monetaryField.style.display = 'none';
            foodField.style.display = 'block';
            quantityField.style.display = 'block';
        } else {
            monetaryField.style.display = 'none';
            foodField.style.display = 'none';
            quantityField.style.display = 'none';
        }
    });
</script>

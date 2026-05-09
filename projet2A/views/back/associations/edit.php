<?php $pageTitle = "Edit Organization"; ?>
<?php $pageSubtitle = "Modify organization information"; ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Edit: <?php echo htmlspecialchars($association['name']); ?></h2>
        </div>
        
        <form method="POST" action="/nutriflow-ai/public/admin/associations/update/<?php echo $association['id']; ?>" class="p-6" id="associationForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name *</label>
                    <input type="text" name="name" id="name" 
                           value="<?php echo htmlspecialchars($association['name']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="error-message text-red-500 text-sm mt-1" data-field="name"></div>
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="text" name="email" id="email"
                           value="<?php echo htmlspecialchars($association['email']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="error-message text-red-500 text-sm mt-1" data-field="email"></div>
                </div>
                
                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="text" name="phone" id="phone"
                           value="<?php echo htmlspecialchars($association['phone']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="error-message text-red-500 text-sm mt-1" data-field="phone"></div>
                </div>
                
                <!-- Tax ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax ID (SIRET) *</label>
                    <input type="text" name="siret" id="siret"
                           value="<?php echo htmlspecialchars($association['siret']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           maxlength="14">
                    <div class="error-message text-red-500 text-sm mt-1" data-field="siret"></div>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="active" <?php echo $association['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $association['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                
                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <input type="text" name="address" id="address"
                           value="<?php echo htmlspecialchars($association['address']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="error-message text-red-500 text-sm mt-1" data-field="address"></div>
                </div>
                
                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                    <input type="text" name="city" id="city"
                           value="<?php echo htmlspecialchars($association['city']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="error-message text-red-500 text-sm mt-1" data-field="city"></div>
                </div>
                
                <!-- Postal Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                    <input type="text" name="postal_code" id="postal_code"
                           value="<?php echo htmlspecialchars($association['postal_code']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           maxlength="5">
                    <div class="error-message text-red-500 text-sm mt-1" data-field="postal_code"></div>
                </div>
                
                <!-- Mission -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mission / Description *</label>
                    <textarea name="mission" id="mission" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($association['mission']); ?></textarea>
                    <div class="error-message text-red-500 text-sm mt-1" data-field="mission"></div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="/nutriflow-ai/public/admin/associations" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

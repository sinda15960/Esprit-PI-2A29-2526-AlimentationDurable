<div class="max-w-4xl mx-auto fade-in">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Banner -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($association['name']); ?></h1>
                    <p class="text-green-100">Certified partner organization</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-full p-3">
                    <i class="fas fa-hand-holding-heart text-3xl"></i>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <!-- Contact information -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-green-600 mr-2"></i>
                        General Information
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-building text-green-600 w-6 mt-1"></i>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Organization Name</p>
                                <p class="font-medium"><?php echo htmlspecialchars($association['name']); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-green-600 w-6 mt-1"></i>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium"><?php echo htmlspecialchars($association['email']); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-phone text-green-600 w-6 mt-1"></i>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Phone</p>
                                <p class="font-medium"><?php echo htmlspecialchars($association['phone']); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-id-card text-green-600 w-6 mt-1"></i>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Tax ID</p>
                                <p class="font-medium"><?php echo htmlspecialchars($association['siret']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                        Address
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-location-dot text-green-600 w-6 mt-1"></i>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Full Address</p>
                                <p class="font-medium"><?php echo nl2br(htmlspecialchars($association['address'])); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-city text-green-600 w-6 mt-1"></i>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">City / Postal Code</p>
                                <p class="font-medium"><?php echo htmlspecialchars($association['city']); ?> - <?php echo htmlspecialchars($association['postal_code']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mission -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bullhorn text-green-600 mr-2"></i>
                    Our Mission
                </h3>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6">
                    <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($association['mission'])); ?></p>
                </div>
            </div>
            
            <!-- Donate button -->
            <div class="text-center pt-4 border-t border-gray-200">
                <a href="/nutriflow-ai/public/don" 
                   class="inline-flex items-center bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:shadow-xl transition transform hover:scale-105">
                    <i class="fas fa-heart mr-2"></i>
                    Donate to this organization
                </a>
                <p class="text-sm text-gray-500 mt-4">
                    <i class="fas fa-lock mr-1"></i> 100% Secure Payment
                </p>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-8">
        <a href="/nutriflow-ai/public/associations" class="text-green-600 hover:text-green-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to organizations list
        </a>
    </div>
</div>
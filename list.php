<div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Our Partner Organizations</h1>
        <p class="text-xl text-gray-600">Discover organizations fighting against food waste</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach($associations as $assoc): ?>
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:transform hover:scale-105 transition duration-300">
            <div class="p-6">
                <div class="mb-4 text-center">
                    <i class="fas fa-hand-holding-heart text-4xl text-green-600"></i>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-800 mb-2 text-center"><?php echo htmlspecialchars($assoc['name']); ?></h3>
                <p class="text-gray-600 mb-4 text-center"><?php echo htmlspecialchars(substr($assoc['mission'], 0, 100)) . '...'; ?></p>
                
                <div class="space-y-2 mb-6">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-map-marker-alt w-5 text-green-600"></i>
                        <span><?php echo htmlspecialchars($assoc['city']); ?></span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-envelope w-5 text-green-600"></i>
                        <span><?php echo htmlspecialchars($assoc['email']); ?></span>
                    </div>
                </div>
                
                <a href="/nutriflow-ai/public/associations/show/<?php echo $assoc['id']; ?>" 
                   class="block text-center bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-xl hover:shadow-lg transition">
                    Learn more
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
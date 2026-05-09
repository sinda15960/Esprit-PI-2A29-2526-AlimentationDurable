<?php $pageTitle = "Détail du don"; ?>
<?php $pageSubtitle = "Informations complètes sur le don #" . $don['id']; ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-white">Don #<?php echo $don['id']; ?></h2>
                <p class="text-green-100 text-sm">Date : <?php echo date('d/m/Y à H:i', strtotime($don['created_at'])); ?></p>
            </div>
            <div class="bg-white/20 backdrop-blur rounded-full px-4 py-2">
                <i class="fas fa-receipt text-white"></i>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Statut actuel -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Statut actuel :</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        <?php echo $don['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : 
                            ($don['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            ($don['status'] == 'delivered' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')); ?>">
                        <?php 
                            echo $don['status'] == 'confirmed' ? 'Confirmé' : 
                                ($don['status'] == 'pending' ? 'En attente' : 
                                ($don['status'] == 'delivered' ? 'Livré' : 'Annulé'));
                        ?>
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations donateur -->
                <div class="border rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-green-600 mr-2"></i>
                        Informations donateur
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Nom complet</p>
                            <p class="font-medium"><?php echo htmlspecialchars($don['donor_name']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium"><?php echo htmlspecialchars($don['donor_email']); ?></p>
                        </div>
                        <?php if($don['donor_phone']): ?>
                        <div>
                            <p class="text-sm text-gray-500">Téléphone</p>
                            <p class="font-medium"><?php echo htmlspecialchars($don['donor_phone']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Informations don -->
                <div class="border rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-gift text-green-600 mr-2"></i>
                        Informations don
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Type de don</p>
                            <p class="font-medium">
                                <?php echo $don['donation_type'] == 'monetary' ? 'Monétaire' : 
                                    ($don['donation_type'] == 'food' ? 'Alimentaire' : 'Équipement'); ?>
                            </p>
                        </div>
                        
                        <?php if($don['donation_type'] == 'monetary'): ?>
                        <div>
                            <p class="text-sm text-gray-500">Montant</p>
                            <p class="font-medium text-green-600 text-xl"><?php echo number_format($don['amount'], 2); ?> dt</p>
                        </div>
                        <?php else: ?>
                        <div>
                            <p class="text-sm text-gray-500">Type d'aliment</p>
                            <p class="font-medium"><?php echo htmlspecialchars($don['food_type']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Quantité</p>
                            <p class="font-medium"><?php echo $don['quantity']; ?> kg</p>
                        </div>
                        <?php endif; ?>
                        
                        <div>
                            <p class="text-sm text-gray-500">Moyen de paiement</p>
                            <p class="font-medium">
                                <?php 
                                    echo $don['payment_method'] == 'card' ? 'Carte bancaire' : 
                                        ($don['payment_method'] == 'paypal' ? 'PayPal' : 'Virement bancaire');
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Message -->
                <?php if($don['message']): ?>
                <div class="md:col-span-2 border rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-comment text-green-600 mr-2"></i>
                        Message du donateur
                    </h3>
                    <p class="text-gray-700 italic">"<?php echo nl2br(htmlspecialchars($don['message'])); ?>"</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                <a href="/nutriflow-ai/public/admin/dons" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
                
                <div class="space-x-3">
                    <form method="POST" action="/nutriflow-ai/public/admin/dons/update-status/<?php echo $don['id']; ?>" class="inline">
                        <select name="status" onchange="this.form.submit()" 
                                class="px-4 py-2 border rounded-lg text-sm">
                            <option value="pending" <?php echo $don['status'] == 'pending' ? 'selected' : ''; ?>>En attente</option>
                            <option value="confirmed" <?php echo $don['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmer</option>
                            <option value="delivered" <?php echo $don['status'] == 'delivered' ? 'selected' : ''; ?>>Marquer livré</option>
                            <option value="cancelled" <?php echo $don['status'] == 'cancelled' ? 'selected' : ''; ?>>Annuler</option>
                        </select>
                    </form>
                    
                    <button onclick="window.print()" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        <i class="fas fa-print mr-2"></i>
                        Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

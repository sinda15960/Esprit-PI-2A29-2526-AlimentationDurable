<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Organizations Management</h2>
        <a href="/nutriflow-ai/public/admin/associations/create" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
            <i class="fas fa-plus mr-2"></i>New Organization
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(empty($associations)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No organizations found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($associations as $assoc): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($assoc['name']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($assoc['email']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($assoc['city']); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full <?php echo ($assoc['status'] == 'active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo ($assoc['status'] == 'active') ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="/nutriflow-ai/public/admin/associations/edit/<?php echo $assoc['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/nutriflow-ai/public/admin/associations/delete/<?php echo $assoc['id']; ?>" onclick="return confirm('Delete this organization?')" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
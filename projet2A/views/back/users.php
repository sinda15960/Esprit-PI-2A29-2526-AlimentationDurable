<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Age</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                <td><?php echo $user['age'] ?? '-'; ?></td>
                <td><span class="role-badge <?php echo $user['role']; ?>"><?php echo $user['role']; ?></span></td>
                <td class="actions">
                    <a href="index.php?action=admin_edit_user&id=<?php echo $user['id']; ?>" class="btn-edit">Edit</a>
                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                        <a href="index.php?action=admin_delete_user&id=<?php echo $user['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
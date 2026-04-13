<div class="edit-form-container">
    <form method="POST" action="index.php?action=admin_edit_user&id=<?php echo $user['id']; ?>">
        <div class="form-group">
            <label for="username">Username *</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="age">Age</label>
            <input type="number" id="age" name="age" value="<?php echo $user['age'] ?? ''; ?>">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role">
                <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">Save Changes</button>
            <a href="index.php?action=admin_users" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
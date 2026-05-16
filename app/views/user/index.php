<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
// MUST be passed from controller
// $users = list of users
?>

<div class="page-header">

    <div>

        <h1>Users Management</h1>

        <p class="page-subtitle">
            Manage all system users
        </p>

    </div>

    <a href="index.php?page=create_user" class="btn-primary">  
        + Create User
    </a>

</div>

<div class="table-container">

    <table class="modern-table">

        <thead>

            <tr>

                <th>#</th>
                <th>User</th>
                <th>Email</th>
                <th>Department</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>

            </tr>

        </thead>

        <tbody>

            <?php if (!empty($users)): ?>

                <?php $count = 1; ?>

                <?php foreach ($users as $user): ?>

                    <tr>

                        <td><?= $count++; ?></td>

                        <td>
                            <div class="user-info">

                                <div class="avatar">
                                    <?= strtoupper(substr($user['name'], 0, 1)); ?>
                                </div>

                                <div>
                                    <strong>
                                        <?= htmlspecialchars($user['name']); ?>
                                    </strong>
                                </div>

                            </div>
                        </td>

                        <td>
                            <?= htmlspecialchars($user['email']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($user['department_id'] ?? 'N/A'); ?>
                        </td>

                        <td>
                            <span class="role-badge">
                                <?= ucfirst($user['role_name'] ?? 'Staff'); ?>
                            </span>
                        </td>

                        <td>
                            <span class="status-active">Active</span>
                        </td>

                        <td>

                            <a href="index.php?page=edit_user&id=<?= $user['id'] ?>" 
                               class="btn-edit">
                                Edit
                            </a>

                            <a href="index.php?page=delete_user&id=<?= $user['id'] ?>" 
                               onclick="return confirm('Delete this user?')" 
                               class="btn-delete">
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" class="empty-state">
                        No users found
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
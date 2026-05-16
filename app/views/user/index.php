<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div>
        <h1>Users Management</h1>
    </div>

    <a href="index.php?page=create_user" class="btn-primary">+ Create User</a>
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
    <th>Actions</th>
</tr>
</thead>

<tbody>

<?php if (!empty($users)): ?>

    <?php $i = 1; foreach ($users as $u): ?>

    <tr>

        <td><?= $i++; ?></td>

        <td>
            <div class="user-info">
                <div class="avatar">
                    <?= strtoupper(substr($u['name'], 0, 1)); ?>
                </div>

                <strong><?= htmlspecialchars($u['name']); ?></strong>
            </div>
        </td>

        <td><?= htmlspecialchars($u['email']); ?></td>

        <td><?= htmlspecialchars($u['department_id'] ?? 'N/A'); ?></td>

        <td><?= htmlspecialchars($u['role'] ?? 'Staff'); ?></td>

        <td>
            <a href="index.php?page=edit_user&id=<?= $u['id'] ?>" class="btn-edit">Edit</a>
            <a href="index.php?page=delete_user&id=<?= $u['id'] ?>" class="btn-delete">Delete</a>
        </td>

    </tr>

    <?php endforeach; ?>

<?php else: ?>

<tr><td colspan="6">No users found</td></tr>

<?php endif; ?>

</tbody>

</table>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
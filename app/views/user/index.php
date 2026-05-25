<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
 <a href="javascript:history.back()" class="back-btn" style="text-decoration: none;">
            ↩️ Back
        </a>
    <h1>Users Management</h1>

    <a href="index.php?page=create_user" class="btn-primary">
        + Create User
    </a>

</div>

<div class="table-container">

<table class="modern-table">

<thead>
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Email</th>
    <th>Department</th>
    <th>Roles</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>

<?php if (!empty($users)): ?>

    <?php $i = 1; foreach ($users as $u): ?>

    <tr>

        <td><?= $i++; ?></td>

        <td><?= htmlspecialchars($u['name']); ?></td>

        <td><?= htmlspecialchars($u['email']); ?></td>

        <td><?= htmlspecialchars($u['department_name'] ?? 'N/A'); ?></td>

        <td>
            <?= htmlspecialchars($u['roles'] ?? 'No Role'); ?>
        </td>

        <td>
            <a href="index.php?page=edit_user&id=<?= $u['id'] ?>">Edit</a>
            <a href="index.php?page=delete_user&id=<?= $u['id'] ?>">Delete</a>
        </td>

    </tr>

    <?php endforeach; ?>

<?php else: ?>

<tr>
    <td colspan="6">No users found</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
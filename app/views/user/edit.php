<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
// SAFETY FALLBACKS (prevents undefined variable crashes)
$editUser = $editUser ?? [];
$departments = $departments ?? [];
$roles = $roles ?? [];
?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Edit User</h1>
            <p>Update user information</p>
        </div>

        <!-- SUCCESS MESSAGE -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- ERROR MESSAGE -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert-error">
                <?= $_SESSION['error'];
                 unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=update_user">

            <input type="hidden" name="id"
                   value="<?= htmlspecialchars($editUser['id'] ?? '') ?>">

            <!-- NAME -->
            <div class="form-group">
                <label>Full Name</label>
                <input type="text"
                       name="name"
                       value="<?= htmlspecialchars($editUser['name'] ?? '') ?>"
                       required>
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label>Email</label>
                <input type="email"
                       name="email"
                       value="<?= htmlspecialchars($editUser['email'] ?? '') ?>"
                       required>
            </div>

            <!-- DEPARTMENT -->
            <div class="form-group">
                <label>Department</label>

                <select name="department_id" required>

                    <option value="">-- Select Department --</option>

                    <?php foreach ($departments as $department): ?>

                        <?php
                            $selected = (
                                isset($editUser['department_id']) &&
                                $editUser['department_id'] == $department['id']
                            ) ? 'selected' : '';
                        ?>

                        <option value="<?= $department['id'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($department['name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>

            <!-- ROLE -->
            <div class="form-group">
                <label>Role</label>

                <select name="role_id" required>

                    <option value="">-- Select Role --</option>

                    <?php foreach ($roles as $role): ?>

                        <?php
                            $selected = (
                                isset($editUser['role_id']) &&
                                $editUser['role_id'] == $role['id']
                            ) ? 'selected' : '';
                        ?>

                        <option value="<?= $role['id'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($role['name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label>New Password</label>
                <input type="password"
                       name="password"
                       placeholder="Leave blank to keep current password">
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password"
                       name="confirm_password"
                       placeholder="Confirm new password">
            </div>

            <button class="btn-submit" type="submit">
                Update User
            </button>

        </form>

    </div>

</div>

<style>
.alert-success{
    background:#dcfce7;
    color:#166534;
    padding:14px;
    border-radius:10px;
    margin-bottom:20px;
    font-weight:bold;
}

.alert-error{
    background:#fee2e2;
    color:#991b1b;
    padding:14px;
    border-radius:10px;
    margin-bottom:20px;
    font-weight:bold;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
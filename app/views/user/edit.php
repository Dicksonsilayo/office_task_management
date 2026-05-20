<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$editUser = $editUser ?? [];
$departments = $departments ?? [];
$roles = $roles ?? [];

// current selected role from DB
$currentRoleId = $editUser['role_id'] ?? null;
?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Edit User</h1>
            <p>Update user information</p>
        </div>

        <!-- SUCCESS -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- ERROR -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=update_user">

            <!-- USER ID -->
            <input type="hidden"
                   name="id"
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

                        <option
                            value="<?= $department['id'] ?>"
                            <?= (($editUser['department_id'] ?? '') == $department['id']) ? 'selected' : '' ?>
                        >
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

                        <option
                            value="<?= $role['id'] ?>"
                            <?= ($currentRoleId == $role['id']) ? 'selected' : '' ?>
                        >
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

            <!-- BUTTON -->
            <button class="btn-submit" type="submit">
                Update User
            </button>

        </form>

    </div>

</div>

<style>

.form-page{
    padding:30px;
}

.form-card{
    background:#fff;
    max-width:650px;
    margin:auto;
    padding:35px;
    border-radius:16px;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
}

.form-header{
    margin-bottom:25px;
}

.form-header h1{
    margin:0;
    font-size:28px;
}

.form-header p{
    color:#666;
    margin-top:5px;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
}

.form-group input,
.form-group select{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:15px;
}

.form-group input:focus,
.form-group select:focus{
    outline:none;
    border-color:#2563eb;
}

.btn-submit{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
}

.btn-submit:hover{
    background:#1d4ed8;
}

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
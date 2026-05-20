<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$departments = $departments ?? [];
$roles = $roles ?? [];
?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Create User</h1>
            <p>Add new system user</p>
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

        <form method="POST" action="index.php?page=store_user">

            <!-- NAME -->
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required>
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>

            <!-- DEPARTMENT -->
            <div class="form-group">
                <label>Department</label>

                <select name="department_id" required>

                    <option value="">-- Select Department --</option>

                    <?php foreach ($departments as $department): ?>

                        <option value="<?= $department['id'] ?>">
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

                        <option value="<?= $role['id'] ?>">
                            <?= htmlspecialchars($role['name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn-submit">
                Create User
            </button>

        </form>

    </div>

</div>

<style>

.form-page{
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding:40px;
    background:#f4f6f9;
    min-height:100vh;
}

.form-card{
    width:100%;
    max-width:700px;
    background:#fff;
    padding:35px;
    border-radius:14px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);

    /* IMPORTANT FIXES */
    overflow:visible;
    height:auto;
}

.form-header{
    margin-bottom:25px;
}

.form-header h1{
    margin:0;
    color:#1e293b;
}

.form-header p{
    color:#64748b;
    margin-top:5px;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#334155;
}

.form-group input,
.form-group select{
    width:100%;
    padding:12px;
    border:1px solid #cbd5e1;
    border-radius:8px;
    font-size:15px;
    background:white;
}

.form-group select{
    cursor:pointer;
}

.btn-submit{
    width:100%;
    padding:14px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    margin-top:10px;
}

.btn-submit:hover{
    background:#1d4ed8;
}

.alert-success{
    background:#dcfce7;
    color:#166534;
    padding:14px;
    border-radius:8px;
    margin-bottom:20px;
}

.alert-error{
    background:#fee2e2;
    color:#991b1b;
    padding:14px;
    border-radius:8px;
    margin-bottom:20px;
}

</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
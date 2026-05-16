<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Create User</h1>
            <p>Add new system user</p>
        </div>

        <!-- SUCCESS MESSAGE -->
        <?php if (!empty($_SESSION['success'])): ?>

            <div class="alert-success">
                <?= $_SESSION['success']; ?>
            </div>

            <?php unset($_SESSION['success']); ?>

        <?php endif; ?>

        <!-- ERROR MESSAGE -->
        <?php if (!empty($_SESSION['error'])): ?>

            <div class="alert-error">
                <?= $_SESSION['error']; ?>
            </div>

            <?php unset($_SESSION['error']); ?>

        <?php endif; ?>

        <form method="POST" action="index.php?page=store_user">

            <!-- NAME -->
            <div class="form-group">

                <label>Full Name</label>

                <input
                    type="text"
                    name="name"
                    required
                >

            </div>

            <!-- EMAIL -->
            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    required
                >

            </div>

            <!-- PASSWORD -->
            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    required
                >

            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">

                <label>Confirm Password</label>

                <input
                    type="password"
                    name="confirm_password"
                    required
                >

            </div>

            <!-- DEPARTMENT -->
            <div class="form-group">

                <label>Department</label>

                <select name="department_id" required>

                    <option value="">
                        -- Select Department --
                    </option>

                    <?php foreach ($departments as $department): ?>

                        <option value="<?= $department['id']; ?>">

                            <?= htmlspecialchars($department['name']); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- ROLE -->
            <div class="form-group">

                <label>Role</label>

                <select name="role_id" required>

                    <option value="">
                        -- Select Role --
                    </option>

                    <?php foreach ($roles as $role): ?>

                        <option value="<?= $role['id']; ?>">

                            <?= htmlspecialchars($role['name']); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- BUTTON -->
            <button class="btn-submit" type="submit">

                Create User

            </button>

        </form>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
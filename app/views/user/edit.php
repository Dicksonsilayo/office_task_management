<?php 

// print_r($user);
// die('');

require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Edit User</h1>
            <p>Update user information</p>
        </div>

        <!-- SUCCESS MESSAGE -->
        <?php if(isset($_SESSION['success'])): ?>

            <div class="alert-success">
                <?= $_SESSION['success']; ?>
            </div>

            <?php unset($_SESSION['success']); ?>

        <?php endif; ?>

        <!-- ERROR MESSAGE -->
        <?php if(isset($_SESSION['error'])): ?>

            <div class="alert-error">
                <?= $_SESSION['error']; ?>
            </div>

            <?php unset($_SESSION['error']); ?>

        <?php endif; ?>

        <form method="POST" action="index.php?page=update_user">

            <input
                type="hidden"
                name="id"
                value="<?= $user['id'] ?? '' ?>"
            >

            <!-- NAME -->
            <div class="form-group">

                <label>Full Name</label>

                <input
                    type="text"
                    name="name"
                    value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                    required
                >

            </div>

            <!-- EMAIL -->
            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                    required
                >

            </div>

            <!-- PASSWORD -->
            <div class="form-group">

                <label>New Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Leave blank to keep old password"
                >

            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">

                <label>Confirm Password</label>

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Confirm new password"
                >

            </div>

            <!-- BUTTON -->
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
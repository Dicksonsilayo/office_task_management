<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Add Visitor</h1>
            <p>Register a new office visitor</p>
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
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=store_visitor">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" required>
            </div>

            <div class="form-group">
                <label>Purpose</label>
                <textarea name="purpose" required></textarea>
            </div>

            <button class="btn-submit">Save Visitor</button>

        </form>

    </div>
</div>

<style>
.form-page {
    padding: 30px;
}

.form-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    max-width: 600px;
    margin: auto;
}

.form-group {
    margin-bottom: 15px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
}

.btn-submit {
    background: #2563eb;
    color: white;
    padding: 12px 18px;
    border: none;
    cursor: pointer;
}

.alert-success {
    background: #dcfce7;
    padding: 10px;
    margin-bottom: 10px;
}

.alert-error {
    background: #fee2e2;
    padding: 10px;
    margin-bottom: 10px;
}
</style>

<script>
/* AUTO HIDE MESSAGES AFTER 5 SECONDS */
setTimeout(() => {
    document.querySelectorAll('.alert-success, .alert-error').forEach(el => {
        el.style.transition = "0.5s";
        el.style.opacity = "0";
        setTimeout(() => el.remove(), 600);
    });
}, 5000);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<script>
setTimeout(() => {
    let msg = document.querySelector('.alert-success');
    if (msg) {
        msg.style.transition = "0.5s";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 5000);
</script>
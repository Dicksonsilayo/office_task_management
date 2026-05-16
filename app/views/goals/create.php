<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php if (isset($_SESSION['error'])): ?>

    <div class="error-message">
        <?= $_SESSION['error']; ?>
    </div>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>


<?php if (isset($_SESSION['success'])): ?>

    <div class="success-message">
        <?= $_SESSION['success']; ?>
    </div>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">

            <h1>Create Goal</h1>

            <p>
                Define organizational goals
            </p>

        </div>

        <form method="POST" action="index.php?page=store_goal">

            <div class="form-group">

                <label>Goal Name</label>

                <input
                    type="text"
                    name="name"
                    required
                >

            </div>

            <div class="form-group">

                <label>Description</label>

                <textarea
                    name="description"
                    rows="5"
                ></textarea>

            </div>

            <button class="btn-submit">

                Save Goal

            </button>

        </form>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
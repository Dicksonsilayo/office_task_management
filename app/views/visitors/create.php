<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">

            <h1>Add Visitor</h1>

            <p>
                Register a new office visitor
            </p>

        </div>

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

            <button class="btn-submit">

                Save Visitor

            </button>

        </form>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
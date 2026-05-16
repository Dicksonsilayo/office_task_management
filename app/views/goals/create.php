<?php require_once __DIR__ . '/../layouts/header.php'; ?>

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
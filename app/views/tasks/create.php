<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Create Task</h1>
            <p>Assign a new task to staff</p>
        </div>

        <!-- =========================
             SUCCESS MESSAGE
        ========================= -->
       <!-- SUCCESS MESSAGE -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="success-message">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- ERROR MESSAGES (ARRAY SUPPORT) -->
<?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
    <div class="error-message">

        <?php foreach ($_SESSION['errors'] as $err): ?>
            <div><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>

    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<!-- SINGLE ERROR (OPTIONAL BACKWARD SUPPORT) -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="error-message">
        <?= htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

        <form method="POST" action="index.php?page=store_task">

            <!-- TITLE -->
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>

            <!-- PRIORITY -->
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" required>
                    <option value="">Select</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <!-- USERS -->
            <div class="form-group">
                <label>Assign To</label>
                <select name="assigned_to" required>
                    <option value="">Select User</option>

                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>">
                                <?= htmlspecialchars($user['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option disabled>No users found</option>
                    <?php endif; ?>

                </select>
            </div>

            <!-- GOALS -->
            <div class="form-group">
                <label>Goal</label>
                <select name="goal_id" required>
                    <option value="">Select Goal</option>

                    <?php if (!empty($goals)): ?>
                        <?php foreach ($goals as $goal): ?>
                            <option value="<?= $goal['id'] ?>">
                                <?= htmlspecialchars($goal['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option disabled>No goals found</option>
                    <?php endif; ?>

                </select>
            </div>

            <!-- DEADLINE -->
            <div class="form-group">
                <label>Deadline</label>
                <input type="date" name="deadline" required>
            </div>

            <!-- NOTES -->
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes"></textarea>
            </div>

            <button class="btn-submit" type="submit">
                Create Task
            </button>
<script>
           setTimeout(() => { const success = document.querySelector('.success-message'); 
           const error = document.querySelector('.error-message');
            if(success){ success.style.display = 'none';

             } 
             if(error){ 
                error.style.display = 'none'; } }, 5000);
            
            </script>
        </form>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
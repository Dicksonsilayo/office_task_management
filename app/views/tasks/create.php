<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Create Task</h1>
            <p>Assign a new task to staff</p>
        </div>

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
                        <?php foreach(($users ?? []) as $user): ?>
                        <option value="<?= $user['id'] ?>">
                            <?= $user['name'] ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <!-- GOALS -->
            <div class="form-group">
                <label>Goal</label>
                <select name="goal_id" required>
                    <option value="">Select Goal</option>

                   <?php foreach(($goals ?? []) as $goal): ?>
                        <option value="<?= $goal['id'] ?>">
                            <?= $goal['name'] ?>
                        </option>
                    <?php endforeach; ?>

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

        </form>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>m
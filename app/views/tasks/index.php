<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$user = Auth::user();
$tasks = $tasks ?? [];

/*
|--------------------------------------------------------------------------
| SAFE COUNTERS
|--------------------------------------------------------------------------
*/
$pending = 0;
$inProgress = 0;
$completed = 0;

foreach ($tasks as $t) {

    $status = $t['status'] ?? 'pending';

    if ($status === 'pending') $pending++;
    elseif ($status === 'in_progress') $inProgress++;
    elseif ($status === 'completed') $completed++;
}
?>

<div class="page-header">

    <div>
        <h1>Tasks</h1>
        <p class="page-subtitle">
            Manage and track all assigned tasks
        </p>
    </div>

    <?php if (($user['role'] ?? '') === 'admin' || ($user['role'] ?? '') === 'hod'): ?>
        <a href="index.php?page=create_task" class="btn-primary">
            + Assign Task
        </a>
    <?php endif; ?>

</div>

<!-- SUMMARY CARDS -->
<div class="stats-grid">

    <div class="stat-card">
        <h3>Pending</h3>
        <p><?= $pending ?></p>
    </div>

    <div class="stat-card">
        <h3>In Progress</h3>
        <p><?= $inProgress ?></p>
    </div>

    <div class="stat-card">
        <h3>Completed</h3>
        <p><?= $completed ?></p>
    </div>

</div>

<!-- TASK LIST -->
<div class="task-container">

<?php if (!empty($tasks)): ?>

    <?php foreach ($tasks as $task): ?>

        <?php
            $id = $task['id'] ?? 0;
            $title = htmlspecialchars($task['title'] ?? 'No title');
            $description = htmlspecialchars($task['description'] ?? 'No description');
            $status = $task['status'] ?? 'pending';
            $goalId = $task['goal_id'] ?? 'N/A';
            $createdAt = $task['created_at'] ?? 'N/A';
        ?>

        <div class="task-card">

            <!-- HEADER -->
            <div class="task-header">

                <h3><?= $title ?></h3>

                <span class="badge <?= $status ?>">
                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                </span>

            </div>

            <!-- DESCRIPTION -->
            <p class="task-description">
                <?= $description ?>
            </p>

            <!-- META -->
            <div class="task-meta">

                <span>📌 Goal: <b>#<?= $goalId ?></b></span><br>

                <span>Assigned on <?= htmlspecialchars($createdAt) ?></span>

            </div>

            <!-- ACTIONS -->
            <div class="task-actions">

                <!-- VIEW -->
                <a href="index.php?page=task_show&id=<?= $id ?>" class="btn-primary">
                    View Details
                </a>

                <!-- STATUS UPDATE -->
                <form method="POST" action="index.php?page=update_task_status">

                    <input type="hidden" name="task_id" value="<?= $id ?>">

                    <?php if ($status !== 'in_progress'): ?>
                        <button type="submit" name="status" value="in_progress" class="btn-warning">
                            Start
                        </button>
                    <?php endif; ?>

                    <?php if ($status !== 'completed'): ?>
                        <button type="submit" name="status" value="completed" class="btn-success">
                            Complete
                        </button>
                    <?php endif; ?>

                </form>

    <?php if ($role === 'admin'): ?>
    <a href="index.php?page=delete_task&id=<?= $id ?>"
       onclick="return confirm('Are you sure you want to delete this task?')"
       class="btn-danger">
        Delete
    </a>


<?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="empty-state">
        <h3>No tasks found</h3>
        <p>Create a task to get started</p>
    </div>

<?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$user = Auth::user();
$tasks = $tasks ?? [];

/*
|--------------------------------------------------------------------------
| ROLE SAFE HANDLING (PIVOT READY)
|--------------------------------------------------------------------------
*/
$userRoles = $user['roles'] ?? [];

if (is_string($userRoles)) {
    $userRoles = explode(',', strtolower($userRoles));
}

$userRoles = array_map('trim', $userRoles);

/*
|--------------------------------------------------------------------------
| COUNTERS
|--------------------------------------------------------------------------
*/
$pending = 0;
$inProgress = 0;
$reviewed = 0;
$satisfied = 0;
$completed = 0;

foreach ($tasks as $t) {

    $status = $t['status'] ?? 'pending';

    if ($status === 'pending') $pending++;
    elseif ($status === 'in_progress') $inProgress++;
    elseif ($status === 'reviewed') $reviewed++;
    elseif ($status === 'satisfied') $satisfied++;
    elseif ($status === 'completed') $completed++;
}
?>

<!-- HEADER -->
<div class="page-header">

    <div>
        <h1>Tasks</h1>
        <p class="page-subtitle">Manage and track all assigned tasks</p>
    </div>

    <?php if (in_array('admin', $userRoles) || in_array('hod', $userRoles)): ?>
        <a href="index.php?page=create_task" class="btn-primary">
            + Assign Task
        </a>
    <?php endif; ?>

</div>

<!-- STATS -->
<div class="stats-grid">

    <div class="stat-card"><h3>Pending</h3><p><?= $pending ?></p></div>
    <div class="stat-card"><h3>In Progress</h3><p><?= $inProgress ?></p></div>
    <div class="stat-card"><h3>Reviewed</h3><p><?= $reviewed ?></p></div>
    <div class="stat-card"><h3>Satisfied</h3><p><?= $satisfied ?></p></div>
    <div class="stat-card"><h3>Completed</h3><p><?= $completed ?></p></div>

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

            <div class="task-header">

                <h3><?= $title ?></h3>

                <span class="badge <?= $status ?>">
                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                </span>

            </div>

            <p class="task-description">
                <?= $description ?>
            </p>

            <div class="task-meta">
                <span>📌 Goal: <b>#<?= $goalId ?></b></span><br>
                <span>Assigned: <?= htmlspecialchars($createdAt) ?></span>
            </div>

            <!-- ACTIONS -->
            <div class="task-actions">

                <!-- VIEW -->
                <a href="index.php?page=task_show&id=<?= $id ?>" class="btn btn-primary">
                    View
                </a>

                <!-- WORKFLOW -->
                <form method="POST" action="index.php?page=update_task_status">

                    <input type="hidden" name="task_id" value="<?= $id ?>">

                    <?php if ($status === 'pending'): ?>
                        <button name="status" value="in_progress" class="btn btn-warning">
                            Start
                        </button>
                    <?php endif; ?>

                    <?php if ($status === 'in_progress'): ?>
                        <button name="status" value="reviewed" class="btn btn-info">
                            Review
                        </button>
                    <?php endif; ?>

                    <?php if ($status === 'reviewed'): ?>
                        <button name="status" value="satisfied" class="btn btn-success">
                            Satisfied
                        </button>

                        <button name="status" value="not_satisfied" class="btn btn-danger">
                            Not OK
                        </button>
                    <?php endif; ?>

                    <?php if ($status === 'satisfied'): ?>
                        <button name="status" value="completed" class="btn btn-success">
                            Close
                        </button>
                    <?php endif; ?>

                </form>

                <!-- DELETE -->
                <?php if (in_array('admin', $userRoles)): ?>
                    <a href="index.php?page=delete_task&id=<?= $id ?>"
                       onclick="return confirm('Delete this task?')"
                       class="btn btn-danger">
                        Delete
                    </a>
                <?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="empty-state">
        No tasks found. Create one to start tracking.
    </div>

<?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
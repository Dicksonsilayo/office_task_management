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
?><style>
    .task-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

/* Make all buttons uniform */
.task-actions a,
.task-actions button {
    padding: 6px 10px;
    font-size: 13px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Colors */
.btn-warning {
     background: #f59e0b; 
     color: white;
     min-width: 200px;
     }
.btn-success { background: #22c55e; color: white; min-width: 200px; }
.btn-danger { background: #ef4444; color: white;  min-width: 200px;}
.btn-primary { background: #3b82f6; color: white; min-width: 200px; }

/* Hover effect */
.task-actions button:hover,
.task-actions a:hover {
    opacity: 0.85;
    transform: scale(1.03);
    transition: 0.2s;
}
</style>

<div class="page-header">

    <div>
        <h1>Tasks</h1>
        <p class="page-subtitle">
            Manage and track all assigned tasks
        </p>
    </div>

  <?php

/*
|--------------------------------------------------------------------------
| FIX FOR PIVOT ROLE SYSTEM
|--------------------------------------------------------------------------
*/
$userRoles = $user['roles'] ?? [];

if (is_string($userRoles)) {
    $userRoles = explode(',', strtolower($userRoles));
}

$userRoles = array_map('trim', $userRoles);

?>

<?php if (
    in_array('admin', $userRoles) ||
    in_array('hod', $userRoles)
): ?>

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

   <?php

$roles = $user['roles'] ?? [];

if (is_string($roles)) {
    $roles = array_map('trim', explode(',', strtolower($roles)));
}

?>

<?php if (in_array('admin', $roles)): ?>

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
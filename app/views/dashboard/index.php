<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Task.php';
require_once __DIR__ . '/../../models/Visitor.php';

$userModel = new User();
$taskModel = new Task();
$visitorModel = new Visitor();

$users = $userModel->getAll();

$tasks = $taskModel->getAllByRole(
    Auth::user()
);

$allVisitors = $visitorModel->getAll();

$todayVisitors = $visitorModel->getToday();

?>

<h1>Admin Dashboard</h1>

<div class="stats">

    <!-- USERS -->
    <div class="card">

        <h3>Total Users</h3>

        <p><?= $data['totalUsers'] ?? 0; ?></p>

        <button
            class="dropdown-btn"
            onclick="toggleDropdown('usersDropdown')"
        >
            View Users ▼
        </button>

        <div
            id="usersDropdown"
            class="dropdown-content"
            style="display:none;"
        >

            <?php if (!empty($users)): ?>

                <?php foreach ($users as $user): ?>

                    <div class="dropdown-item">

                        <strong>
                            <?= htmlspecialchars($user['name'] ?? 'N/A'); ?>
                        </strong>

                        <br>

                        <small>
                            <?= htmlspecialchars($user['email'] ?? 'N/A'); ?>
                        </small>

                        <br>

                        <small>
                            Role:
                            <?= htmlspecialchars($user['roles'] ?? 'N/A'); ?>
                        </small>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="dropdown-item">
                    No users found
                </div>

            <?php endif; ?>

        </div>

    </div>

    <!-- VISITORS -->
    <div class="card">

        <h3>Total Visitors</h3>

        <p><?= $data['totalVisitors'] ?? 0; ?></p>

        <button
            class="dropdown-btn"
            onclick="toggleDropdown('visitorsDropdown')"
        >
            View Visitors ▼
        </button>

        <div
            id="visitorsDropdown"
            class="dropdown-content"
            style="display:none;"
        >

            <strong>Today's Visitors</strong>

            <br><br>

            <?php if (!empty($todayVisitors)): ?>

                <?php foreach ($todayVisitors as $visitor): ?>

                    <div class="dropdown-item">

                        <strong>
                            <?= htmlspecialchars($visitor['full_name'] ?? 'N/A'); ?>
                        </strong>

                        <br>

                        <small>
                            <?= htmlspecialchars($visitor['purpose'] ?? 'N/A'); ?>
                        </small>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="dropdown-item">
                    No visitors today
                </div>

            <?php endif; ?>

            <hr>

            <strong>All Visitors</strong>

            <br><br>

            <?php if (!empty($allVisitors)): ?>

                <?php foreach ($allVisitors as $visitor): ?>

                    <div class="dropdown-item">

                        <strong>
                            <?= htmlspecialchars($visitor['full_name'] ?? 'N/A'); ?>
                        </strong>

                        <br>

                        <small>
                            <?= htmlspecialchars($visitor['purpose'] ?? 'N/A'); ?>
                        </small>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="dropdown-item">
                    No visitors found
                </div>

            <?php endif; ?>

        </div>

    </div>

    <!-- TASKS -->
    <div class="card">

        <h3>Total Tasks</h3>

        <p><?= $data['totalTasks'] ?? 0; ?></p>

        <button
            class="dropdown-btn"
            onclick="toggleDropdown('tasksDropdown')"
        >
            View Tasks ▼
        </button>

        <div
            id="tasksDropdown"
            class="dropdown-content"
            style="display:none;"
        >

            <?php if (!empty($tasks)): ?>

                <?php foreach ($tasks as $task): ?>

                    <div class="dropdown-item">

                        <strong>
                            <?= htmlspecialchars($task['title'] ?? 'N/A'); ?>
                        </strong>

                        <br>

                        <small>
                            Status:
                            <?= htmlspecialchars($task['status'] ?? 'pending'); ?>
                        </small>

                        <br>

                        <small>
                            Priority:
                            <?= htmlspecialchars($task['priority'] ?? 'medium'); ?>
                        </small>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="dropdown-item">
                    No tasks found
                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<script>

function toggleDropdown(id)
{
    let dropdown = document.getElementById(id);

    if (dropdown.style.display === "none") {

        dropdown.style.display = "block";

    } else {

        dropdown.style.display = "none";
    }
}

</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
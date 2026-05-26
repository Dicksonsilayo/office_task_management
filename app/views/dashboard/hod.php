<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">

    <div>
        <h1>HOD Dashboard</h1>
        <p class="page-subtitle">
            Department overview and performance
        </p>
    </div>

</div>


<!-- OPTIONAL SECTION: HOD QUICK VIEW -->
<div class="task-container">

    <h3 style="margin-top:20px;">Department Overview</h3>

    <div class="task-card">
        <p>
            Welcome <?= htmlspecialchars($data['user']['name'] ?? 'HOD'); ?> 👋
        </p>

        <p style="color:#666;">
            Here you can monitor staff performance, tasks, and departmental activity.
        </p>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
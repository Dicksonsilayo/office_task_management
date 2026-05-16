<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h1>Admin Dashboard</h1>

<div class="stats">

    <div class="card">
        <h3>Total Users</h3>
        <p><?= $data['totalUsers'] ?? []; ?></p>
    </div>

    <div class="card">
        <h3>Total Visitors</h3>
        <p><?= $data['totalVisitors'] ?? []; ?></p>
    </div>

    <div class="card">
        <h3>Total Tasks</h3>
        <p><?= $data['totalTasks'] ?? []; ?></p>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
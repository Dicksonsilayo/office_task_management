<h3>
    Welcome, <?= htmlspecialchars($data['user']['name'] ?? 'User'); ?>
</h3>

<div class="cards">

    <div class="card">
        <h2><?= $data['totalUsers'] ?? 0; ?></h2>
        <p>Total Users</p>
    </div>

    <div class="card">
        <h2><?= $data['totalTasks'] ?? 0; ?></h2>
        <p>Total Tasks</p>
    </div>

    <div class="card">
        <h2><?= $data['totalVisitors'] ?? 0; ?></h2>
        <p>Total Visitors</p>
    </div>

</div>
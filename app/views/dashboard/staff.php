<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h1>Staff Dashboard</h1>

<h3>
    Welcome, <?= $data['user']['name']; ?>
</h3>

<p>
    Here you can manage your assigned tasks.
</p>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
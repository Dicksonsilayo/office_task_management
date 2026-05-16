<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h1>Reception Dashboard</h1>

<h3>
    Welcome, <?= $data['user']['name']; ?>
</h3>

<p>
    Manage visitor attendance here.
</p>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
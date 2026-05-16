<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">

    <div>

        <h1>Goals</h1>

        <p class="page-subtitle">
            Manage organizational goals
        </p>

    </div>

    <a href="index.php?page=create_goal" class="btn-primary">
        + Create Goal
    </a>

</div>

<div class="table-container">

    <table class="modern-table">

        <thead>

            <tr>
                <th>#</th>
                <th>Goal Name</th>
                <th>Description</th>
            </tr>

        </thead>

        <tbody>

            <?php if (!empty($goals)): ?>

                <?php $count = 1; ?>

                <?php foreach ($goals as $goal): ?>

                    <tr>

                        <!-- SAFE COUNT (NO REAL ID DISPLAY) -->
                        <td><?= $count++; ?></td>

                        <td>
                            <?= htmlspecialchars($goal['name']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($goal['description'] ?? 'N/A'); ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="3" class="empty-state">
                        No goals found
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php if ($message = Flash::get('success')): ?>

    <div class="success-message">
        <?= htmlspecialchars($message); ?>
    </div>

<?php endif; ?>

<?php if ($message = Flash::get('error')): ?>

    <div class="error-message">
        <?= $message; // allow <br> ?>
    </div>

<?php endif; ?>
<div class="page-header">

    <div>
 <a href="javascript:history.back()" class="back-btn" style="text-decoration: none;">
            ↩️ Back
        </a>
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
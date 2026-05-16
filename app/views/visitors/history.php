<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">

    <div>

        <h1>Visitor Attendance History</h1>

        <p class="page-subtitle">
            Track visitor check-in and check-out activity
        </p>

    </div>

</div>

<div class="table-container">

<table class="modern-table">

    <thead>

        <tr>

            <th>ID</th>
            <th>Visitor</th>
            <th>Phone</th>
            <th>Purpose</th>
            <th>Check In</th>
            <th>Check Out</th>

        </tr>

    </thead>

    <tbody>

        <?php if(!empty($history)): ?>

            <?php foreach($history as $row): ?>

                <tr>

                    <td>
                        #<?= $row['id'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['full_name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['phone']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['purpose']) ?>
                    </td>

                    <td>
                        <?= $row['check_in'] ?>
                    </td>

                    <td>

                        <?= $row['check_out'] ?? 'Still Inside' ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="6" class="empty-state">

                    No attendance history found

                </td>

            </tr>

        <?php endif; ?>

    </tbody>

</table>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Task Audit Report</title>

<link rel="stylesheet" href="/assets/css/print.css">

</head>

<body>

<a href="javascript:history.back()" class="no-print" style="margin:15px; display:inline-block;">
    ↩️ Back
</a>

<div class="print-page">

    <div class="print-card">

        <div class="print-title">
            TASK AUDIT REPORT
        </div>

        <!-- META -->
        <p><strong>Task:</strong> <?= htmlspecialchars($task['title'] ?? '') ?></p>
        <p><strong>Assigned To:</strong> <?= htmlspecialchars($task['assigned_to_name'] ?? '') ?></p>
        <p><strong>Department:</strong> <?= htmlspecialchars($task['department_name'] ?? '') ?></p>

        <hr>

        <!-- TABLE -->
        <table class="print-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

            <?php if (!empty($logs)): ?>

                <?php foreach ($logs as $i => $log): ?>

                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($log['user_name'] ?? 'System') ?></td>
                        <td><?= htmlspecialchars($log['action'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($log['description'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($log['created_at'] ?? '-') ?></td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5">No logs found</td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

        <button class="print-btn no-print" onclick="window.print()">
            Print Report
        </button>

    </div>

</div>

</body>
</html>
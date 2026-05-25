<tbody>
     <a href="javascript:history.back()" class="back-btn" style="text-decoration: none;">
            ↩️ Back
        </a>
<style>

body {
    font-family: Arial, sans-serif;
    font-size: 12px;
    margin: 20px;
    color: #000;
}

/* TITLE */
h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 18px;
}

/* META INFO */
.meta {
    margin-bottom: 20px;
    font-size: 13px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

/* HEADERS */
th {
    background: #e5e5e5;
    border: 1px solid #000;
    padding: 8px;
    text-align: left;
    font-weight: bold;
}

/* CELLS */
td {
    border: 1px solid #000;
    padding: 8px;
    vertical-align: top;
}

/* PRINT IMPROVEMENTS */
@media print {

    body {
        margin: 0;
    }

    button {
        display: none;
    }

    table {
        page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

}

</style>
<?php if (!empty($logs)): ?>

    <?php foreach ($logs as $index => $log): ?>

        <tr>

            <td>
                <?= $index + 1 ?>
            </td>

            <td>
                <?= htmlspecialchars($log['user_name'] ?? 'System') ?>
            </td>

            <td>

                <?php

                $action = $log['action'] ?? '-';

                $labels = [

                    'task_created'   => 'Task Created',
                    'task_assigned'  => 'Task Assigned',
                    'status_changed' => 'Status Updated',
                    'comment_added'  => 'Comment Added',
                    'task_deleted'   => 'Task Deleted'

                ];

                echo htmlspecialchars(
                    $labels[$action]
                    ?? ucfirst(str_replace('_', ' ', $action))
                );

                ?>

            </td>

            <td>

                <?= htmlspecialchars(
                    $log['description']
                    ?? 'No description available'
                ) ?>

            </td>

            <td>

                <?= htmlspecialchars(
                    $log['created_at']
                    ?? '-'
                ) ?>

            </td>

        </tr>

    <?php endforeach; ?>

<?php else: ?>

    <tr>

        <td colspan="5" style="text-align:center; padding:20px;">

            No audit logs found

        </td>

    </tr>

<?php endif; ?>

</tbody>
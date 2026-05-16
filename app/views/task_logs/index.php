<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h1>Task Activity Logs</h1>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Task ID</th>
        <th>Approved By</th>
        <th>Action</th>
        <th>Date</th>
    </tr>

    <?php foreach($logs as $log): ?>

        <tr>
            <td><?php echo $log['id']; ?></td>
            <td><?php echo $log['task_id']; ?></td>
            <td><?php echo $log['approver']; ?></td>
            <td><?php echo $log['action']; ?></td>
            <td><?php echo $log['created_at']; ?></td>
        </tr>

    <?php endforeach; ?>

</table>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
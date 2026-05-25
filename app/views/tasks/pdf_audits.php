

    <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Task Audit Report</title>

<style>

/* ===== A4 PAGE SETUP ===== */
@page {
    size: A4;
    margin: 20mm;
}

body {
    font-family: Arial, sans-serif;
    font-size: 12px;
    background: #f5f5f5;
    margin: 0;
}

/* ===== A4 SHEET CONTAINER ===== */
.a4-page {
    width: 210mm;
    min-height: 297mm;
    margin: auto;
    background: white;
    padding: 20mm;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

/* ===== HEADER ===== */
h2 {
    text-align: center;
    margin-bottom: 15px;
    font-size: 18px;
}

/* ===== META SECTION ===== */
.meta {
    margin-bottom: 20px;
    font-size: 13px;
    line-height: 1.6;
}

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th {
    background: #e5e5e5;
    border: 1px solid #000;
    padding: 8px;
    text-align: left;
}

td {
    border: 1px solid #000;
    padding: 8px;
    vertical-align: top;
}

/* ===== PRINT CONTROL ===== */
@media print {

    body {
        background: white;
    }

    .a4-page {
        width: 100%;
        min-height: auto;
        box-shadow: none;
        padding: 0;
    }

    .no-print {
        display: none;
    }
}

</style>

</head>

<body>
    </style>
</head>

<body>
 <a href="javascript:history.back()" class="back-btn" style="text-decoration: none;">
            ↩️ Back
        </a>
<h2>Task Audit Report</h2>

<div class="meta">
    <strong>Task:</strong> <?= htmlspecialchars($task['title'] ?? '') ?><br>
    <strong>Assigned To:</strong> <?= htmlspecialchars($task['assigned_to_name'] ?? '') ?><br>
    <strong>Department:</strong> <?= htmlspecialchars($task['department_name'] ?? '') ?><br>
</div>

<table>
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

                <td><?= htmlspecialchars($log['description'] ?? 'No details') ?></td>

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

</body>
</html>
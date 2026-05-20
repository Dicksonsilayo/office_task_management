<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">

    <div>
        <h1>Visitor Attendance History</h1>

        <p class="page-subtitle">
            Track visitor check-in and check-out activity
        </p>
    </div>

    <!-- PRINT BUTTON -->
    <div class="header-actions">

        <button onclick="printVisitorLogs()" class="btn-print">
            Print Logs
        </button>

    </div>

</div>

<div class="table-container" id="printArea">

    <table class="modern-table">

        <thead>

            <tr>
                <th>ID</th>
                <th>Visitor</th>
                <th>Phone</th>
                <th>Purpose</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Status</th>
            </tr>

        </thead>

        <tbody>

        <?php if (!empty($history)): ?>

            <?php foreach ($history as $row): ?>

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
                        <?= date('d M Y H:i', strtotime($row['check_in'])) ?>
                    </td>

                    <td>

                        <?php if (!empty($row['check_out'])): ?>

                            <?= date('d M Y H:i', strtotime($row['check_out'])) ?>

                        <?php else: ?>

                            <span class="status-inside">
                                Still Inside
                            </span>

                        <?php endif; ?>

                    </td>

                    <td>

                        <?php if (empty($row['check_out'])): ?>

                            <span class="badge-inside">
                                Inside
                            </span>

                        <?php else: ?>

                            <span class="badge-outside">
                                Checked Out
                            </span>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="7" class="empty-state">
                    No attendance history found
                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

<style>

.header-actions{
    display:flex;
    gap:10px;
}

.btn-print{
    background:#2563eb;
    color:white;
    border:none;
    padding:12px 18px;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}

.btn-print:hover{
    background:#1d4ed8;
}

.badge-inside{
    background:#dcfce7;
    color:#166534;
    padding:6px 10px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.badge-outside{
    background:#fee2e2;
    color:#991b1b;
    padding:6px 10px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.status-inside{
    color:#16a34a;
    font-weight:bold;
}

@media print {

    body *{
        visibility:hidden;
    }

    #printArea,
    #printArea *{
        visibility:visible;
    }

    #printArea{
        position:absolute;
        left:0;
        top:0;
        width:100%;
    }

    .btn-print{
        display:none;
    }
}

</style>

<script>

function printVisitorLogs()
{
    window.print();
}

</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
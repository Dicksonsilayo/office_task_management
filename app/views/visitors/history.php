<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">

    <div>

        <a href="javascript:history.back()" 
           class="back-btn" 
           style="text-decoration:none;">
            ↩️ Back
        </a>

        <h1>Visitor Attendance History</h1>

        <p class="page-subtitle">
            Track visitor check-in and check-out activity
        </p>

    </div>

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
                <th>#</th>
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

            <?php $count = 1; ?>

            <?php foreach ($history as $row): ?>

                <tr>

                    <!-- SAFE COUNTER -->
                    <td>
                        <?= $count++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['full_name']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['phone']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['purpose']); ?>
                    </td>

                    <td>

                        <?php if (!empty($row['check_in'])): ?>

                            <?= date('d M Y H:i', strtotime($row['check_in'])); ?>

                        <?php else: ?>

                            -

                        <?php endif; ?>

                    </td>

                    <td>

                        <?php if (!empty($row['check_out'])): ?>

                            <?= date('d M Y H:i', strtotime($row['check_out'])); ?>

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

/*
|--------------------------------------------------------------------------
| PAGE HEADER
|--------------------------------------------------------------------------
*/

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:20px;
}

.page-header h1{
    margin:0;
    color:#0f172a;
}

.page-subtitle{
    margin-top:5px;
    color:#64748b;
}

/*
|--------------------------------------------------------------------------
| HEADER ACTIONS
|--------------------------------------------------------------------------
*/

.header-actions{
    display:flex;
    gap:10px;
}

/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

.table-container{
    background:white;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

.modern-table{
    width:100%;
    border-collapse:collapse;
}

.modern-table thead{
    background:#0f172a;
    color:white;
}

.modern-table th{
    padding:16px;
    text-align:left;
    font-size:14px;
}

.modern-table td{
    padding:16px;
    border-bottom:1px solid #e2e8f0;
}

.modern-table tr:hover{
    background:#f8fafc;
}

/*
|--------------------------------------------------------------------------
| BUTTON
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| BADGES
|--------------------------------------------------------------------------
*/

.badge-inside{
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.badge-outside{
    background:#fee2e2;
    color:#991b1b;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.status-inside{
    color:#16a34a;
    font-weight:bold;
}

/*
|--------------------------------------------------------------------------
| EMPTY STATE
|--------------------------------------------------------------------------
*/

.empty-state{
    text-align:center;
    padding:30px;
    color:#64748b;
}

/*
|--------------------------------------------------------------------------
| PRINT
|--------------------------------------------------------------------------
*/

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

    .btn-print,
    .back-btn{
        display:none;
    }
}

/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media(max-width:768px){

    .page-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .modern-table{
        font-size:14px;
    }

    .modern-table th,
    .modern-table td{
        padding:12px;
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
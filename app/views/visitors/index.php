<?php

require_once __DIR__ . '/../layouts/header.php';

?>

<div class="page-header">

    <div>
        <a href="javascript:history.back()" 
           class="back-btn" 
           style="text-decoration:none;">
            ↩️ Back
        </a>

        <h1>Visitors</h1>

        <p class="page-subtitle">
            Manage office visitors and attendance
        </p>
    </div>

    <div style="display:flex; gap:10px;">

        <a href="index.php?page=visitor_history" class="btn-secondary">
            Attendance History
        </a>

        <a href="index.php?page=create_visitor" class="btn-primary">
            + Add Visitor
        </a>

    </div>

</div>

<!-- FLASH MESSAGES -->

<?php if (!empty($_SESSION['success'])): ?>

    <div class="alert success-alert">

        <?= $_SESSION['success']; ?>

    </div>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>


<?php if (!empty($_SESSION['error'])): ?>

    <div class="alert error-alert">

        <?= $_SESSION['error']; ?>

    </div>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>


<br>

<div class="table-container">

    <table class="modern-table">

        <thead>

            <tr>

                <th>#</th>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Action</th>

            </tr>

        </thead>

        <tbody>

        <?php if (!empty($visitors)): ?>

            <?php $count = 1; ?>

            <?php foreach ($visitors as $visitor): ?>

                <tr>

                    <td>
                        <?= $count++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($visitor['full_name']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($visitor['phone']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($visitor['purpose']); ?>
                    </td>

                    <td>

                        <?php if ($visitor['status'] === 'inside'): ?>

                            <span class="badge badge-success">
                                Inside
                            </span>

                        <?php else: ?>

                            <span class="badge badge-danger">
                                Outside
                            </span>

                        <?php endif; ?>

                    </td>

                    <td>

                        <?php if ($visitor['status'] === 'outside'): ?>

                            <form method="POST"
                                  action="index.php?page=checkin_visitor">

                                <input type="hidden"
                                       name="visitor_id"
                                       value="<?= $visitor['id']; ?>">

                                <button type="submit"
                                        class="btn-success">

                                    Check In

                                </button>

                            </form>

                        <?php else: ?>

                            <form method="POST"
                                  action="index.php?page=checkout_visitor">

                                <input type="hidden"
                                       name="visitor_id"
                                       value="<?= $visitor['id']; ?>">

                                <button type="submit"
                                        class="btn-danger">

                                    Check Out

                                </button>

                            </form>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="6" class="empty-state">

                    No visitors found

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
| BADGES
|--------------------------------------------------------------------------
*/

.badge{
    padding:7px 12px;
    border-radius:30px;
    font-size:13px;
    font-weight:600;
}

.badge-success{
    background:#dcfce7;
    color:#166534;
}

.badge-danger{
    background:#fee2e2;
    color:#991b1b;
}

/*
|--------------------------------------------------------------------------
| BUTTONS
|--------------------------------------------------------------------------
*/

.btn-primary,
.btn-secondary,
.btn-success,
.btn-danger{
    border:none;
    padding:10px 16px;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    text-decoration:none;
    display:inline-block;
    transition:0.2s;
}

.btn-primary{
    background:#2563eb;
    color:white;
}

.btn-primary:hover{
    background:#1d4ed8;
}

.btn-secondary{
    background:#e2e8f0;
    color:#0f172a;
}

.btn-secondary:hover{
    background:#cbd5e1;
}

.btn-success{
    background:#16a34a;
    color:white;
}

.btn-success:hover{
    background:#15803d;
}

.btn-danger{
    background:#dc2626;
    color:white;
}

.btn-danger:hover{
    background:#b91c1c;
}

/*
|--------------------------------------------------------------------------
| ALERTS
|--------------------------------------------------------------------------
*/

.alert{
    padding:14px 16px;
    border-radius:10px;
    margin-bottom:15px;
    font-weight:500;
}

.success-alert{
    background:#dcfce7;
    color:#166534;
}

.error-alert{
    background:#fee2e2;
    color:#991b1b;
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

/*
|--------------------------------------------------------------------------
| AUTO HIDE ALERTS
|--------------------------------------------------------------------------
*/

setTimeout(() => {

    document.querySelectorAll('.alert').forEach(alert => {

        alert.style.transition = "0.4s";
        alert.style.opacity = "0";

        setTimeout(() => {

            alert.remove();

        }, 400);

    });

}, 4000);

</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
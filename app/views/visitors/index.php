<?php

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../models/VisitorAttendance.php';

$attendanceModel = new VisitorAttendance();

?>

<div class="page-header">
     <a href="javascript:history.back()" class="back-btn" style="text-decoration: none;">
            ↩️ Back
        </a>
    <h1>Visitors</h1>

    <a href="index.php?page=create_visitor" class="btn-primary">
        + Add Visitor
    </a>
</div>

<br>

<table class="modern-table" border="0" width="100%" cellpadding="12">

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

            <?php
                $status = $attendanceModel->getStatus($visitor['id']);
            ?>

            <tr>

                <!-- SAFE COUNTER (NO DB ID EXPOSED) -->
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
                    <?php if ($status === 'inside'): ?>
                        <span class="badge badge-success">Inside</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Outside</span>
                    <?php endif; ?>
                </td>

                <td>

                    <?php if ($status === 'outside'): ?>

                        <form method="POST" action="index.php?page=checkin_visitor">

                            <input type="hidden" name="visitor_id" value="<?= $visitor['id']; ?>">

                            <button type="submit" class="btn-success">
                                Check In
                            </button>

                        </form>

                    <?php else: ?>

                        <form method="POST" action="index.php?page=checkout_visitor">

                            <input type="hidden" name="visitor_id" value="<?= $visitor['id']; ?>">

                            <button type="submit" class="btn-danger">
                                Check Out
                            </button>

                        </form>

                    <?php endif; ?>

                </td>

            </tr>

        <?php endforeach; ?>

    <?php else: ?>

        <tr>
            <td colspan="6" style="text-align:center;">
                No visitors found
            </td>
        </tr>

    <?php endif; ?>

    </tbody>

</table>
<script>
function refreshVisitorStatus() {
    fetch("index.php?page=visitors&ajax=1")
        .then(res => res.text())
        .then(html => {
            document.querySelector("tbody").innerHTML = html;
        });
}

// refresh every 10 seconds
setInterval(refreshVisitorStatus, 10000);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
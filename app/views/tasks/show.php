<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$user = Auth::user();
$task = $task ?? [];
?>

<div class="page-wrapper">

    <!-- ALERTS -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert-error">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- TASK DETAIL -->
    <div class="task-detail">

        <!-- HEADER -->
        <div class="task-top">

            <div>
                <h1 class="task-title">
                    <?= htmlspecialchars($task['title'] ?? 'Untitled Task') ?>
                </h1>

                <p class="task-description">
                    <?= htmlspecialchars($task['description'] ?? '') ?>
                </p>
            </div>

            <?php $status = $task['status'] ?? 'pending'; ?>

            <span class="badge <?= htmlspecialchars($status) ?>">
                <?= ucfirst(str_replace('_', ' ', $status)) ?>
            </span>

        </div>

        <!-- META -->
        <div class="task-meta">

            <div class="meta-card">
                <strong>Assigned By</strong>
                <span><?= htmlspecialchars($task['assigned_by_name'] ?? 'N/A') ?></span>
            </div>

            <div class="meta-card">
                <strong>Assigned To</strong>
                <span><?= htmlspecialchars($task['assigned_to_name'] ?? 'N/A') ?></span>
            </div>

            <div class="meta-card">
                <strong>Department</strong>
                <span><?= htmlspecialchars($task['department_name'] ?? 'N/A') ?></span>
            </div>

            <div class="meta-card">
                <strong>Goal</strong>
                <span><?= htmlspecialchars($task['goal_name'] ?? 'N/A') ?></span>
            </div>

            <div class="meta-card">
                <strong>Priority</strong>
                <span><?= ucfirst($task['priority'] ?? 'low') ?></span>
            </div>

            <div class="meta-card">
                <strong>Created At</strong>
                <span><?= htmlspecialchars($task['created_at'] ?? 'N/A') ?></span>
            </div>

        </div>

        <!-- =========================
            AUDIT TIMELINE (UPGRADED)
        ========================== -->

        <h2 class="section-title">Activity Timeline</h2>

        <?php if (!empty($logs)): ?>

            <div class="timeline-wrapper">

                <?php foreach ($logs as $log): ?>

                    <?php
                        $action = $log['action'] ?? 'unknown';

                        // ICON LOGIC (FIXED HERE)
                        $icon = '📝';

                        if ($action === 'task_created') $icon = '🟢';
                        elseif ($action === 'task_assigned') $icon = '📌';
                        elseif ($action === 'status_changed') $icon = '🔵';
                        elseif ($action === 'comment_added') $icon = '💬';
                        elseif ($action === 'task_deleted') $icon = '🔴';
                    ?>

                    <div class="timeline-item">

                        <div class="timeline-icon <?= htmlspecialchars($action) ?>">
                            <?= $icon ?>
                        </div>

                        <div class="timeline-content">

                            <div class="timeline-header">
                                <strong>
                                    <?= htmlspecialchars($log['user_name'] ?? 'System') ?>
                                </strong>

                                <span class="timeline-time">
                                    <?= htmlspecialchars($log['created_at'] ?? '') ?>
                                </span>
                            </div>

                            <div class="timeline-action">
                                <?= ucfirst(str_replace('_', ' ', $action)) ?>
                            </div>

                            <p class="audit-message">
                                <?= htmlspecialchars($log['description'] ?? '') ?>
                            </p>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                No activity recorded yet.
            </div>

        <?php endif; ?>

        <!-- COMMENTS -->
        <h2 class="section-title">Comments</h2>

        <form method="POST" action="index.php?page=add_comment" class="comment-form">

            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">

            <textarea name="description" placeholder="Write comment..." required></textarea>

            <button type="submit" class="btn-comment">Add Comment</button>

        </form>

        <?php if (!empty($comments)): ?>

            <?php foreach ($comments as $comment): ?>

                <div class="comment-box">

                    <strong><?= htmlspecialchars($comment['user_name'] ?? 'Unknown') ?></strong>

                    <p><?= htmlspecialchars($comment['description'] ?? '') ?></p>

                    <small><?= htmlspecialchars($comment['created_at'] ?? '') ?></small>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="empty-state">No comments yet</div>

        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
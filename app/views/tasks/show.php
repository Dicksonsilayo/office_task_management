<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../core/Flash.php'; ?>

<?php
$user = Auth::user();
$task = $task ?? [];
?>

<div class="task-page">

    <!-- FLASH -->
    <?php if ($msg = Flash::get('success')): ?>
        <div class="toast success"><?= htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <?php if ($msg = Flash::get('error')): ?>
        <div class="toast error"><?= htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <!-- TOP BAR -->
    <div class="top-bar no-print">

        <a href="javascript:history.back()" class="btn-back">← Back</a>

        <button onclick="window.print()" class="btn-primary">
            🖨 Print Report
        </button>

    </div>

    <!-- TASK CARD -->
    <div class="task-card">

        <!-- HEADER -->
        <div class="task-header">

            <div>
                <h1><?= htmlspecialchars($task['title'] ?? 'Untitled Task') ?></h1>
                <p><?= htmlspecialchars($task['description'] ?? '') ?></p>
            </div>

            <span class="status <?= $task['status'] ?? 'pending' ?>">
                <?= ucfirst(str_replace('_',' ', $task['status'] ?? 'pending')) ?>
            </span>

        </div>

        <!-- META GRID -->
        <div class="meta-grid">

            <div><label>Assigned By</label><p><?= htmlspecialchars($task['assigned_by_name'] ?? 'N/A') ?></p></div>
            <div><label>Assigned To</label><p><?= htmlspecialchars($task['assigned_to_name'] ?? 'N/A') ?></p></div>
            <div><label>Department</label><p><?= htmlspecialchars($task['department_name'] ?? 'N/A') ?></p></div>
            <div><label>Goal</label><p><?= htmlspecialchars($task['goal_name'] ?? 'N/A') ?></p></div>
            <div><label>Priority</label><p><?= ucfirst($task['priority'] ?? 'low') ?></p></div>
            <div><label>Created</label><p><?= htmlspecialchars($task['created_at'] ?? 'N/A') ?></p></div>

        </div>

        <!-- TIMELINE -->
        <h2 class="section-title">Activity Timeline</h2>

        <div class="timeline">

            <?php if (!empty($logs)): ?>

                <?php foreach ($logs as $log): ?>

                    <div class="timeline-item">

                        <div class="dot"></div>

                        <div class="content">

                            <div class="row">
                                <strong><?= htmlspecialchars($log['user_name'] ?? 'System') ?></strong>
                                <span><?= htmlspecialchars($log['created_at'] ?? '') ?></span>
                            </div>

                            <p class="action">
                                <?= ucfirst(str_replace('_',' ', $log['action'] ?? '')) ?>
                            </p>

                            <p class="desc">
                                <?= htmlspecialchars($log['description'] ?? '') ?>
                            </p>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>
                <p class="empty">No activity recorded yet</p>
            <?php endif; ?>

        </div>

        <!-- COMMENTS -->
        <h2 class="section-title">Comments</h2>

        <form method="POST" action="index.php?page=add_comment" class="comment-box no-print">

            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">

            <textarea name="description" placeholder="Write a comment..." required></textarea>

            <button class="btn-primary full">Add Comment</button>

        </form>

        <?php if (!empty($comments)): ?>

            <div class="comments">

                <?php foreach ($comments as $comment): ?>

                    <div class="comment">

                        <strong><?= htmlspecialchars($comment['user_name'] ?? 'Unknown') ?></strong>

                        <p><?= htmlspecialchars($comment['description'] ?? '') ?></p>

                        <small><?= htmlspecialchars($comment['created_at'] ?? '') ?></small>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <p class="empty">No comments yet</p>
        <?php endif; ?>

    </div>

</div>

<style>

/* PAGE */
.task-page{
    padding:30px;
    background:#f4f6f9;
    min-height:100vh;
}

/* TOP BAR */
.top-bar{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
}

.btn-back{
    text-decoration:none;
    padding:10px 15px;
    background:#e2e8f0;
    border-radius:10px;
    color:#1e293b;
    font-weight:600;
}

.btn-primary{
    background:#2563eb;
    color:#fff;
    padding:10px 16px;
    border:none;
    border-radius:10px;
    cursor:pointer;
}

/* CARD */
.task-card{
    background:#fff;
    border-radius:16px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.06);
}

/* HEADER */
.task-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px;
}

.task-header h1{
    margin:0;
    font-size:26px;
}

.task-header p{
    color:#64748b;
    margin-top:6px;
}

/* STATUS */
.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.status.pending{ background:#fef3c7; color:#92400e; }
.status.completed{ background:#dcfce7; color:#166534; }
.status.in_progress{ background:#dbeafe; color:#1e40af; }

/* META GRID */
.meta-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
    margin-top:25px;
}

.meta-grid div{
    background:#f8fafc;
    padding:12px;
    border-radius:12px;
}

.meta-grid label{
    font-size:12px;
    color:#64748b;
}

.meta-grid p{
    margin:5px 0 0;
    font-weight:600;
}

/* TIMELINE */
.timeline{
    margin-top:20px;
    border-left:2px solid #e2e8f0;
    padding-left:20px;
}

.timeline-item{
    margin-bottom:20px;
    position:relative;
}

.dot{
    width:10px;
    height:10px;
    background:#2563eb;
    border-radius:50%;
    position:absolute;
    left:-6px;
    top:5px;
}

.content{
    background:#f8fafc;
    padding:12px;
    border-radius:12px;
}

.row{
    display:flex;
    justify-content:space-between;
    font-size:13px;
    color:#64748b;
}

.action{
    font-weight:600;
    margin:5px 0;
}

.desc{
    font-size:14px;
    color:#334155;
}

/* COMMENTS */
.comment-box textarea{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #cbd5e1;
}

.comments{
    margin-top:15px;
}

.comment{
    background:#f8fafc;
    padding:12px;
    border-radius:12px;
    margin-bottom:10px;
}

/* EMPTY */
.empty{
    text-align:center;
    color:#94a3b8;
    margin-top:10px;
}

/* TOAST */
.toast{
    padding:12px;
    border-radius:10px;
    margin-bottom:10px;
    font-weight:600;
}

.toast.success{ background:#dcfce7; color:#166534; }
.toast.error{ background:#fee2e2; color:#991b1b; }

/* RESPONSIVE */
@media(max-width:768px){
    .meta-grid{
        grid-template-columns:1fr;
    }

    .task-header{
        flex-direction:column;
    }
}

</style>
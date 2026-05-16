<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>

.task-detail{
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
    max-width:1000px;
    margin:30px auto;
}

.task-title{
    font-size:32px;
    margin-bottom:10px;
    color:#1e293b;
}

.task-description{
    color:#475569;
    margin-bottom:25px;
    line-height:1.7;
}

.task-meta{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
    margin-bottom:30px;
}

.meta-card{
    background:#f8fafc;
    padding:15px;
    border-radius:12px;
    border:1px solid #e2e8f0;
}

.meta-card strong{
    display:block;
    margin-bottom:6px;
    color:#334155;
}

.badge{
    display:inline-block;
    padding:6px 14px;
    border-radius:20px;
    background:#2563eb;
    color:white;
    font-size:14px;
    font-weight:bold;
}

.section-title{
    margin:25px 0 15px;
    color:#1e293b;
}

.timeline{
    list-style:none;
    padding:0;
}

.timeline li{
    background:#f8fafc;
    border-left:4px solid #2563eb;
    padding:15px;
    margin-bottom:15px;
    border-radius:8px;
}

.timeline small{
    color:#64748b;
}

.comment-form{
    margin-top:20px;
}

.comment-form textarea{
    width:100%;
    min-height:120px;
    padding:15px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    resize:none;
    outline:none;
    font-size:15px;
}

.comment-form textarea:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

.btn-comment{
    margin-top:15px;
    background:#2563eb;
    color:white;
    border:none;
    padding:12px 22px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    transition:.3s;
}

.btn-comment:hover{
    background:#1d4ed8;
}

.comment-box{
    background:#f8fafc;
    padding:18px;
    border-radius:12px;
    margin-top:18px;
    border:1px solid #e2e8f0;
}

.comment-box strong{
    color:#1e293b;
}

.comment-box p{
    margin:10px 0;
    color:#475569;
    line-height:1.6;
}

.comment-box small{
    color:#64748b;
}

.empty-state{
    background:#f8fafc;
    padding:20px;
    border-radius:10px;
    color:#64748b;
}

</style>

<div class="task-detail">

    <!-- TASK TITLE -->
    <h1 class="task-title">
        <?= htmlspecialchars($task['title']) ?>
    </h1>

    <!-- DESCRIPTION -->
    <p class="task-description">
        <?= htmlspecialchars($task['description']) ?>
    </p>

    <!-- META -->
    <div class="task-meta">

        <div class="meta-card">
            <strong>Assigned By</strong>
            <?= htmlspecialchars($task['assigned_by_name']) ?>
        </div>

        <div class="meta-card">
            <strong>Assigned To</strong>
            <?= htmlspecialchars($task['assigned_to_name']) ?>
        </div>

        <div class="meta-card">
            <strong>Goal</strong>
            <?= htmlspecialchars($task['goal_name']) ?>
        </div>

        <div class="meta-card">
            <strong>Status</strong>
            <span class="badge">
                <?= ucfirst($task['status']) ?>
            </span>
        </div>

    </div>

    <!-- TIMELINE -->
    <h2 class="section-title">
        Task Timeline
    </h2>

    <?php if (!empty($logs)): ?>

        <ul class="timeline">

            <?php foreach ($logs as $log): ?>

                <li>

                    <strong>
                        <?= htmlspecialchars($log['user_name']) ?>
                    </strong>

                    <br><br>

                    <?= htmlspecialchars($log['action']) ?>

                    <br><br>

                    <small>
                        <?= $log['created_at'] ?>
                    </small>

                </li>

            <?php endforeach; ?>

        </ul>

    <?php else: ?>

        <div class="empty-state">
            No activity yet.
        </div>

    <?php endif; ?>

    <!-- COMMENTS -->
    <h2 class="section-title">
        Comments
    </h2>

    <!-- COMMENT FORM -->
    <form
        method="POST"
        action="index.php?page=add_comment"
        class="comment-form"
    >

        <input
            type="hidden"
            name="task_id"
            value="<?= $task['id'] ?>"
        >

        <textarea
            name="description"
            placeholder="Write your comment..."
            required
        ></textarea>

        <button
            type="submit"
            class="btn-comment"
        >
            Add Comment
        </button>

    </form>

    <!-- COMMENT LIST -->
    <?php if (!empty($comments)): ?>

        <?php foreach ($comments as $comment): ?>

            <div class="comment-box">

                <strong>
                    <?= htmlspecialchars($comment['user_name']) ?>
                </strong>

                <p>
                    <?= htmlspecialchars($comment['description']) ?>
                </p>

                <small>
                    <?= $comment['created_at'] ?>
                </small>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="empty-state">
            No comments yet.
        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="notification-page">

    <div class="notification-top">

        <div>
             <a href="javascript:history.back()" class="back-btn" style="text-decoration: none;">
            ↩️ Back
        </a>
            <h1>Notifications</h1>
            <p>Recent system updates</p>
        </div>

        <a href="index.php?page=mark_all_read"
           class="mark-btn">
            Mark All Read
        </a>

    </div>

    <?php if (!empty($notifications)): ?>

        <div class="notification-list">

            <?php foreach ($notifications as $n): ?>

                <div class="notification-card <?= $n['status'] ?>">

                    <div class="notif-left">

                        <div class="notif-icon">
                            🔔
                        </div>

                    </div>

                    <div class="notif-content">

                        <p>
                            <?= htmlspecialchars($n['message']) ?>
                        </p>

                        <small>
                            <?= date(
                                'd M Y H:i',
                                strtotime($n['created_at'])
                            ) ?>
                        </small>

                    </div>

                    <div class="notif-action">

                        <?php if ($n['status'] === 'unread'): ?>

                            <a href="index.php?page=mark_notification_read&id=<?= $n['id'] ?>">
                                Read
                            </a>

                        <?php else: ?>

                            <span class="read-badge">
                                Read
                            </span>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="empty-box">
            <h3>No notifications</h3>
            <p>You're all caught up.</p>
        </div>

    <?php endif; ?>

</div>

<style>

.notification-page{
    padding:25px;
}

.notification-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.notification-top h1{
    margin:0;
    color:#1e293b;
}

.notification-top p{
    margin-top:5px;
    color:#64748b;
}

.mark-btn{
    background:#2563eb;
    color:white;
    padding:10px 16px;
    border-radius:8px;
    text-decoration:none;
    font-size:14px;
}

.notification-list{
    display:flex;
    flex-direction:column;
    gap:15px;
}

.notification-card{
    background:white;
    border-radius:14px;
    padding:18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    border-left:5px solid #3b82f6;
}

.notification-card.unread{
    background:#eff6ff;
}

.notif-left{
    font-size:22px;
}

.notif-content{
    flex:1;
}

.notif-content p{
    margin:0;
    color:#1e293b;
    font-weight:500;
}

.notif-content small{
    color:#64748b;
}

.notif-action a{
    background:#2563eb;
    color:white;
    padding:7px 12px;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
}

.read-badge{
    background:#dcfce7;
    color:#166534;
    padding:7px 12px;
    border-radius:6px;
    font-size:13px;
}

.empty-box{
    background:white;
    padding:40px;
    border-radius:14px;
    text-align:center;
}

</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
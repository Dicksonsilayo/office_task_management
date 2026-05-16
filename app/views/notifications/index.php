<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <div>
        <h1>Notifications</h1>
        <p class="page-subtitle">Your system alerts</p>
    </div>

    <a href="index.php?page=mark_all_notifications_read" class="btn-primary">
        Mark All Read
    </a>
</div>

<div class="notif-page">

    <?php if (!empty($notifications)): ?>

        <?php foreach ($notifications as $n): ?>

            <div class="notif-card <?= $n['status'] ?>">

                <div class="notif-content">
                    <p><?= htmlspecialchars($n['message']) ?></p>
                    <small><?= $n['created_at'] ?></small>
                </div>

                <div class="notif-actions">

                    <?php if ($n['status'] === 'unread'): ?>
                        <a href="index.php?page=mark_notification_read&id=<?= $n['id'] ?>"
                           class="btn-small">
                            Mark as read
                        </a>
                    <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="empty-state">
            No notifications yet 🎉
        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
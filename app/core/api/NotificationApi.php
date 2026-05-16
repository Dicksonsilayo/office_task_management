<?php

require_once __DIR__ . '/../config/database.php';

class Notification {

    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function create($userId, $taskId = null, $message)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO notifications (user_id, task_id, message, status)
            VALUES (?, ?, ?, 'unread')
        ");

        $stmt->bind_param("iis", $userId, $taskId, $message);
        return $stmt->execute();
    }

    public function getByUser($userId)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLatest($userId)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM notifications
            WHERE user_id = ?
            ORDER BY id DESC
            LIMIT 5
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countUnread($userId)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM notifications
            WHERE user_id = ? AND status = 'unread'
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    public function markAsRead($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE notifications SET status = 'read'
            WHERE id = ?
        ");

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
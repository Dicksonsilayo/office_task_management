<?php

class Notification {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE NOTIFICATION
    |--------------------------------------------------------------------------
    */
    public function create($userId, $taskId, $message)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO notifications (user_id, task_id, message, status)
            VALUES (?, ?, ?, 'unread')
        ");

        $stmt->bind_param("iis", $userId, $taskId, $message);
        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER NOTIFICATIONS
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | GET LATEST (for dropdown)
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | COUNT UNREAD
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | MARK AS READ
    |--------------------------------------------------------------------------
    */
    public function markAsRead($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE notifications
            SET status = 'read'
            WHERE id = ?
        ");

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function exists($userId, $taskId, $message)
{
    $stmt = $this->conn->prepare("
        SELECT id FROM notifications
        WHERE user_id = ? AND task_id = ? AND message = ?
    ");

    $stmt->bind_param("iis", $userId, $taskId, $message);
    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}
public function markAllRead($userId)
{
    $stmt = $this->conn->prepare("
        UPDATE notifications
        SET status = 'read'
        WHERE user_id = ?
    ");

    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}
/*
|--------------------------------------------------------------------------
| GENERATE OVERDUE NOTIFICATIONS
|--------------------------------------------------------------------------
*/
public function generateOverdueNotifications()
{
    $query = "
        SELECT *
        FROM tasks
        WHERE deadline IS NOT NULL
        AND deadline < NOW()
        AND status != 'completed'
    ";

    $result = $this->conn->query($query);

    while ($task = $result->fetch_assoc()) {

        $userId = $task['assigned_to'];

        $message = 'Task "' . $task['title'] . '" is overdue';

        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE NOTIFICATIONS
        |--------------------------------------------------------------------------
        */
        if (!$this->exists($userId, $task['id'], $message)) {

            $this->create(
                $userId,
                $task['id'],
                $message
            );
        }
    }
}

}
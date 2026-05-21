<?php

require_once __DIR__ . '/../configs/database.php';

class TaskLog
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE LOG
    |--------------------------------------------------------------------------
    */
    public function create($taskId, $userId, $action, $description)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO task_logs (
                task_id,
                approved_by,
                action,
                description
            )
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iiss",
            $taskId,
            $userId,
            $action,
            $description
        );

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | GET TASK LOGS
    |--------------------------------------------------------------------------
    */
    public function getByTask($taskId)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                tl.*,
                u.name AS user_name
            FROM task_logs tl

            LEFT JOIN users u
                ON u.id = tl.approved_by

            WHERE tl.task_id = ?

            ORDER BY tl.created_at DESC
        ");

        $stmt->bind_param("i", $taskId);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
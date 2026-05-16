<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    | CREATE TASK LOG
    |--------------------------------------------------------------------------
    */
    public function create($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO task_logs (
                task_id,
                user_id,
                action,
                created_at
            )
            VALUES (?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            "iis",
            $data['task_id'],
            $data['user_id'],
            $data['action']
        );

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | QUICK LOG METHOD
    |--------------------------------------------------------------------------
    */
    public function log($taskId, $userId, $action)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO task_logs (
                task_id,
                user_id,
                action,
                created_at
            )
            VALUES (?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            "iis",
            $taskId,
            $userId,
            $action
        );

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL LOGS
    |--------------------------------------------------------------------------
    */
    public function getAll()
    {
        $result = $this->conn->query("
            SELECT
                task_logs.*,
                users.name AS user_name,
                tasks.title AS task_title
            FROM task_logs

            LEFT JOIN users
                ON users.id = task_logs.user_id

            LEFT JOIN tasks
                ON tasks.id = task_logs.task_id

            ORDER BY task_logs.id DESC
        ");

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET LOGS BY TASK
    |--------------------------------------------------------------------------
    */
    public function getByTask($taskId)
    {
        $stmt = $this->conn->prepare("
            SELECT
                task_logs.*,
                users.name AS user_name
            FROM task_logs

            LEFT JOIN users
                ON users.id = task_logs.user_id

            WHERE task_logs.task_id = ?

            ORDER BY task_logs.id DESC
        ");

        $stmt->bind_param("i", $taskId);

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE LOG
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("
            DELETE FROM task_logs
            WHERE id = ?
        ");

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
<?php

require_once __DIR__ . '/../configs/database.php';

class Task
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */
    public function updateStatus($taskId, $status)
    {
        $stmt = $this->conn->prepare("
            UPDATE tasks 
            SET status = ? 
            WHERE id = ?
        ");

        $stmt->bind_param("si", $status, $taskId);
        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE-BASED TASK FETCH (PIVOT SAFE)
    |--------------------------------------------------------------------------
    */
    public function getAllByRole($user)
    {
        $db = $this->conn;

        // GET ROLE FROM PIVOT
        $stmt = $db->prepare("
            SELECT r.name
            FROM roles r
            INNER JOIN role_user ru ON ru.role_id = r.id
            WHERE ru.user_id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $user['id']);
        $stmt->execute();

        $roleData = $stmt->get_result()->fetch_assoc();
        $role = strtolower($roleData['name'] ?? 'staff');

        $userId = $user['id'];
        $deptId = $user['department_id'];

        if ($role === 'admin') {
            $res = $db->query("SELECT * FROM tasks ORDER BY id DESC");
            return $res->fetch_all(MYSQLI_ASSOC);
        }

        if ($role === 'hod') {
            $stmt = $db->prepare("
                SELECT * FROM tasks
                WHERE department_id = ?
                ORDER BY id DESC
            ");
            $stmt->bind_param("i", $deptId);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        $stmt = $db->prepare("
            SELECT * FROM tasks
            WHERE assigned_to = ?
            ORDER BY id DESC
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE TASK
    |--------------------------------------------------------------------------
    */
    public function create($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO tasks (
                title,
                description,
                priority,
                deadline,
                notes,
                assigned_by,
                assigned_to,
                goal_id,
                status,
                department_id
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssiissi",
            $data['title'],
            $data['description'],
            $data['priority'],
            $data['deadline'],
            $data['notes'],
            $data['assigned_by'],
            $data['assigned_to'],
            $data['goal_id'],
            $data['status'],
            $data['department_id']
        );

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY ID
    |--------------------------------------------------------------------------
    */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT t.*,
                u1.name AS assigned_by_name,
                u2.name AS assigned_to_name,
                g.name AS goal_name
            FROM tasks t
            LEFT JOIN users u1 ON t.assigned_by = u1.id
            LEFT JOIN users u2 ON t.assigned_to = u2.id
            LEFT JOIN goals g ON t.goal_id = g.id
            WHERE t.id = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /*
    |--------------------------------------------------------------------------
    | COMMENTS
    |--------------------------------------------------------------------------
    */
    public function getComments($taskId)
    {
        $stmt = $this->conn->prepare("
            SELECT c.*, u.name AS user_name
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.task_id = ?
            ORDER BY c.created_at DESC
        ");

        $stmt->bind_param("i", $taskId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addComment($taskId, $userId, $description)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO comments (task_id, user_id, description)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("iis", $taskId, $userId, $description);
        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | LOGS
    |--------------------------------------------------------------------------
    */
    public function getLogs($taskId)
    {
        $stmt = $this->conn->prepare("
            SELECT tl.*, u.name AS user_name
            FROM task_logs tl
            LEFT JOIN users u ON tl.approved_by = u.id
            WHERE tl.task_id = ?
            ORDER BY tl.created_at DESC
        ");

        $stmt->bind_param("i", $taskId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addLog($taskId, $userId, $action)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO task_logs (task_id, approved_by, action)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("iis", $taskId, $userId, $action);
        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("
            DELETE FROM tasks 
            WHERE id = ?
        ");

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    /*
|--------------------------------------------------------------------------
| CHECK IF USER CAN ACCESS TASK
|--------------------------------------------------------------------------
*/
public function canAccessTask($taskId, $user)
{
    // GET TASK
    $stmt = $this->conn->prepare("
        SELECT * FROM tasks
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $taskId);
    $stmt->execute();

    $task = $stmt->get_result()->fetch_assoc();

    // TASK NOT FOUND
    if (!$task) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER ROLE FROM PIVOT TABLE
    |--------------------------------------------------------------------------
    */
    $roleStmt = $this->conn->prepare("
        SELECT r.name
        FROM roles r
        INNER JOIN role_user ru
            ON ru.role_id = r.id
        WHERE ru.user_id = ?
        LIMIT 1
    ");

    $roleStmt->bind_param("i", $user['id']);
    $roleStmt->execute();

    $roleData = $roleStmt->get_result()->fetch_assoc();

    $role = strtolower($roleData['name'] ?? 'staff');

    /*
    |--------------------------------------------------------------------------
    | ACCESS RULES
    |--------------------------------------------------------------------------
    */

    // ADMIN → ALL TASKS
    if ($role === 'admin') {
        return true;
    }

    // HOD → ONLY DEPARTMENT TASKS
    if ($role === 'hod') {

        return (
            $task['department_id'] == $user['department_id']
        );
    }

    // STAFF → ONLY OWN TASKS
    if ($role === 'staff') {

        return (
            $task['assigned_to'] == $user['id']
        );
    }

    return false;
}
}
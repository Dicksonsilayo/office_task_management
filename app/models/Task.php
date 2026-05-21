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
    | GET TASKS BY ROLE
    |--------------------------------------------------------------------------
    */
    public function getAllByRole($user)
    {
        $db = $this->conn;

        /*
        |--------------------------------------------------------------------------
        | GET USER ROLE FROM PIVOT
        |--------------------------------------------------------------------------
        */
        $stmt = $db->prepare("
            SELECT r.name
            FROM roles r

            INNER JOIN role_user ru
                ON ru.role_id = r.id

            WHERE ru.user_id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $user['id']);

        $stmt->execute();

        $roleData = $stmt->get_result()->fetch_assoc();

        $role = strtolower($roleData['name'] ?? 'staff');

        $userId = $user['id'];
        $departmentId = $user['department_id'];

        /*
        |--------------------------------------------------------------------------
        | ADMIN → ALL TASKS
        |--------------------------------------------------------------------------
        */
        if ($role === 'admin') {

            $query = "
                SELECT *
                FROM tasks
                ORDER BY id DESC
            ";

            $result = $db->query($query);

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        /*
        |--------------------------------------------------------------------------
        | HOD → DEPARTMENT TASKS
        |--------------------------------------------------------------------------
        */
        if ($role === 'hod') {

            $stmt = $db->prepare("
                SELECT *
                FROM tasks
                WHERE department_id = ?
                ORDER BY id DESC
            ");

            $stmt->bind_param("i", $departmentId);

            $stmt->execute();

            return $stmt
                ->get_result()
                ->fetch_all(MYSQLI_ASSOC);
        }

        /*
        |--------------------------------------------------------------------------
        | STAFF → OWN TASKS
        |--------------------------------------------------------------------------
        */
        $stmt = $db->prepare("
            SELECT *
            FROM tasks
            WHERE assigned_to = ?
            ORDER BY id DESC
        ");

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
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

        $stmt->execute();

        return $this->conn->insert_id;
    }

    /*
    |--------------------------------------------------------------------------
    | GET TASK BY ID
    |--------------------------------------------------------------------------
    */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT 
    t.*,
    g.name AS goal_name,
    d.name AS department_name,
    u1.name AS assigned_by_name,
    u2.name AS assigned_to_name
FROM tasks t
LEFT JOIN goals g ON t.goal_id = g.id
LEFT JOIN departments d ON t.department_id = d.id
LEFT JOIN users u1 ON t.assigned_by = u1.id
LEFT JOIN users u2 ON t.assigned_to = u2.id
WHERE t.id = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND TASK
    |--------------------------------------------------------------------------
    */
    public function find($id)
    {
        return $this->getById($id);
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

        $stmt->bind_param(
            "si",
            $status,
            $taskId
        );

        return $stmt->execute();
    }



    private function log($taskId, $userId, $action, $description)
{
    require_once __DIR__ . '/TaskLog.php';

    $log = new TaskLog();
    $log->create($taskId, $userId, $action, $description);
}

    /*
    |--------------------------------------------------------------------------
    | DELETE TASK
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
    | GET COMMENTS
    |--------------------------------------------------------------------------
    */
    public function getComments($taskId)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                c.*,
                u.name AS user_name

            FROM comments c

            LEFT JOIN users u
                ON c.user_id = u.id

            WHERE c.task_id = ?

            ORDER BY c.created_at DESC
        ");

        $stmt->bind_param("i", $taskId);

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | ADD COMMENT
    |--------------------------------------------------------------------------
    */
    public function addComment(
        $taskId,
        $userId,
        $description
    )
    {
        $stmt = $this->conn->prepare("
            INSERT INTO comments (
                task_id,
                user_id,
                description
            )
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "iis",
            $taskId,
            $userId,
            $description
        );

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | GET TASK LOGS
    |--------------------------------------------------------------------------
    */
    public function getLogs($taskId)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                tl.*,
                u.name AS user_name

            FROM task_logs tl

            LEFT JOIN users u
                ON tl.approved_by = u.id

            WHERE tl.task_id = ?

            ORDER BY tl.created_at DESC
        ");

        $stmt->bind_param("i", $taskId);

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK TASK ACCESS
    |--------------------------------------------------------------------------
    */
    public function canAccessTask($taskId, $user)
    {
        /*
        |--------------------------------------------------------------------------
        | GET TASK
        |--------------------------------------------------------------------------
        */
        $stmt = $this->conn->prepare("
            SELECT *
            FROM tasks
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $taskId);

        $stmt->execute();

        $task = $stmt
            ->get_result()
            ->fetch_assoc();

        if (!$task) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | GET USER ROLE
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

        $roleStmt->bind_param(
            "i",
            $user['id']
        );

        $roleStmt->execute();

        $roleData = $roleStmt
            ->get_result()
            ->fetch_assoc();

        $role = strtolower(
            $roleData['name'] ?? 'staff'
        );

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */
        if ($role === 'admin') {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | HOD
        |--------------------------------------------------------------------------
        */
        if ($role === 'hod') {

            return (
                $task['department_id']
                == $user['department_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STAFF
        |--------------------------------------------------------------------------
        */
        if ($role === 'staff') {

            return (
                $task['assigned_to']
                == $user['id']
            );
        }

        return false;
    }
}
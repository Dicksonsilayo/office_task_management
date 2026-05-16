    <?php

    error_reporting(E_ALL);
    ini_set('display_errors',1);

    require_once __DIR__ . '/../configs/database.php';
    require_once __DIR__ . '/../models/Task.php';

    class Task {

        private $conn;

        public function __construct() {
            $this->conn = (new Database())->connect();
        }

        public function getConnection() {
            return $this->conn;
        }

        public function updateStatus($taskId, $status) {

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
        | GET ALL TASKS
        |--------------------------------------------------------------------------
        */public function getAllByRole($user)
    {
        $role = strtolower($user['role'] ?? '');
        $userId = $user['id'];
        $deptId = $user['department_id'];

        $stmt = null;

        if ($role === 'admin') {

            $result = $this->conn->query("
                SELECT * FROM tasks ORDER BY id DESC
            ");

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        if ($role === 'hod') {

            $stmt = $this->conn->prepare("
                SELECT * FROM tasks 
                WHERE department_id = ?
                ORDER BY id DESC
            ");

            $stmt->bind_param("i", $deptId);
            $stmt->execute();
        }

        if ($role === 'staff') {

            $stmt = $this->conn->prepare("
                SELECT * FROM tasks 
                WHERE assigned_to = ?
                ORDER BY id DESC
            ");

            $stmt->bind_param("i", $userId);
            $stmt->execute();
        }

        return $stmt
            ? $stmt->get_result()->fetch_all(MYSQLI_ASSOC)
            : [];
    }



        public function getOverdueTasks()
    {
        $stmt = $this->conn->query("
            SELECT * FROM tasks
            WHERE deadline < NOW()
            AND status != 'completed'
        ");

        return $stmt->fetch_all(MYSQLI_ASSOC);
    }
        public function getById($id) {

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
        public function getComments($taskId) {

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

        public function addComment($taskId, $userId, $description) {

            $stmt = $this->conn->prepare("
                INSERT INTO comments (task_id, user_id, description)
                VALUES (?, ?, ?)
            ");

            $stmt->bind_param("iis", $taskId, $userId, $description);

            return $stmt->execute();
        }

        /*
        |--------------------------------------------------------------------------
        | TASK LOGS
        |--------------------------------------------------------------------------
        */
        public function getLogs($taskId) {

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

        public function addLog($taskId, $userId, $action) {

            $stmt = $this->conn->prepare("
                INSERT INTO task_logs (task_id, approved_by, action)
                VALUES (?, ?, ?)
            ");

            $stmt->bind_param("iis", $taskId, $userId, $action);

            return $stmt->execute();
        }
        public function create($data) {

        $stmt = $this->conn->prepare("
            INSERT INTO tasks 
            (title, description, priority, deadline, notes, assigned_by, assigned_to, goal_id, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssiiss",
            $data['title'],
            $data['description'],
            $data['priority'],
            $data['deadline'],
            $data['notes'],
            $data['assigned_by'],
            $data['assigned_to'],
            $data['goal_id'],
            $data['status']
        );

        return $stmt->execute();
    }
    public function getByDepartment($deptId)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM users WHERE department_id = ?
        ");
        $stmt->bind_param("i", $deptId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function canAccessTask($taskId, $user)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM tasks WHERE id = ?
        ");
        $stmt->bind_param("i", $taskId);
        $stmt->execute();

        $task = $stmt->get_result()->fetch_assoc();

        if (!$task) return false;

        // admin
        if ($user['role'] === 'admin') return true;

        // hod (department)
        if ($user['role'] === 'hod') {
            return $task['department_id'] == $user['department_id'];
        }

        // staff
        if ($user['role'] === 'staff') {
            return $task['assigned_to'] == $user['id'];
        }

        return false;
    }



    }
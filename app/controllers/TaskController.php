<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Goal.php';

require_once __DIR__ . '/../models/Notification.php';

class TaskController
{
    private $taskModel;

    public function __construct()
    {
        $this->taskModel = new Task();
    }

    /*
    |--------------------------------------------------------------------------
    | TASK LIST (ROLE SAFE)
    |--------------------------------------------------------------------------
    */public function index()
{
    Auth::requireLogin();

    $user = Auth::user();

    if (!$user) {
        die("Session invalid - please login again");
    }

    $taskModel = new Task();

    $tasks = $taskModel->getAllByRole($user);

    require __DIR__ . '/../views/tasks/index.php';
}
    /*
    |--------------------------------------------------------------------------
    | SHOW TASK
    |--------------------------------------------------------------------------
    */
    public function show()
    {
        Auth::requireLogin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            die("Task ID missing");
        }

        $task = $this->taskModel->getById($id);

        if (!$task) {
            die("Task not found");
        }

        $comments = $this->taskModel->getComments($id);
        $logs = $this->taskModel->getLogs($id);

        require __DIR__ . '/../views/tasks/show.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        Auth::requireLogin();

        $users = (new User())->getAll();
        $goals = (new Goal())->getAll();

        require __DIR__ . '/../views/tasks/create.php';
    }

    /*
    |--------------------------------------------------------------------------
    | STORE TASK (FIXED - NO OVERWRITE BUG)
    |--------------------------------------------------------------------------
    */public function store()
{
    Auth::requireLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $user = Auth::user();

    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'] ?? '',
        'priority' => $_POST['priority'] ?? 'low',
        'deadline' => $_POST['deadline'] ?? null,
        'notes' => $_POST['notes'] ?? '',
        'assigned_by' => $user['id'],
        'assigned_to' => (int) $_POST['assigned_to'],
        'goal_id' => $_POST['goal_id'] ?? null,
        'status' => 'pending',

        // 🔥 CRITICAL FIX
        'department_id' => $user['department_id'] ?? null
    ];

    $this->taskModel->create($data);

    header("Location: index.php?page=tasks");
    exit;
}
    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */
    public function updateStatus()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $taskId = (int) $_POST['task_id'];
            $status = $_POST['status'];

            $this->taskModel->updateStatus($taskId, $status);

            header("Location: index.php?page=tasks");
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ADD COMMENT
    |--------------------------------------------------------------------------
    */
    public function addComment()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $taskId = (int) $_POST['task_id'];
            $description = trim($_POST['description']);
            $userId = Auth::user()['id'];

            if ($description === '') {
                $_SESSION['error'] = "Comment cannot be empty";
                header("Location: index.php?page=task_show&id=" . $taskId);
                exit;
            }

            $this->taskModel->addComment($taskId, $userId, $description);

            header("Location: index.php?page=task_show&id=" . $taskId);
            exit;
        }
    }
}
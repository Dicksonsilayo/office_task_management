<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../configs/database.php';

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
    | TASK LIST
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        Auth::requireLogin();

        $user = Auth::user();

        $tasks = $this->taskModel->getAllByRole($user);

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

        $user = Auth::user();

        $id = (int) ($_GET['id'] ?? 0);

        // VALIDATE TASK ID
        if ($id <= 0) {

            $_SESSION['error'] = "Invalid task ID";

            header("Location: index.php?page=tasks");
            exit;
        }

        // CHECK ACCESS
        if (!$this->taskModel->canAccessTask($id, $user)) {

            $_SESSION['error'] = "Access denied";

            header("Location: index.php?page=tasks");
            exit;
        }

        // GET TASK
        $task = $this->taskModel->getById($id);

        if (!$task) {

            $_SESSION['error'] = "Task not found";

            header("Location: index.php?page=tasks");
            exit;
        }

        // GET COMMENTS + LOGS
        $comments = $this->taskModel->getComments($id);
        $logs     = $this->taskModel->getLogs($id);

        require __DIR__ . '/../views/tasks/show.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE TASK FORM
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
    | STORE TASK
    |--------------------------------------------------------------------------
    */
    public function store()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("Location: index.php?page=tasks");
            exit;
        }

        $user = Auth::user();

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];

        // VALIDATION
        if ($title === '') {
            $errors[] = "Title is required";
        }

        if (strlen($title) > 150) {
            $errors[] = "Title too long";
        }

        if ($description === '') {
            $errors[] = "Description required";
        }

        if (strlen($description) > 200) {
            $errors[] = "Description too long";
        }

        if (empty($_POST['assigned_to'])) {
            $errors[] = "Assign task to user";
        }

        // HANDLE ERRORS
        if (!empty($errors)) {

            $_SESSION['errors'] = $errors;

            header("Location: index.php?page=create_task");
            exit;
        }

        // PREPARE DATA
        $data = [

            'title' => $title,
            'description' => $description,
            'priority' => $_POST['priority'] ?? 'low',
            'deadline' => $_POST['deadline'] ?? null,
            'notes' => $_POST['notes'] ?? '',

            'assigned_by' => $user['id'],
            'assigned_to' => (int) $_POST['assigned_to'],

            'goal_id' => $_POST['goal_id'] ?? null,

            'status' => 'pending',

            'department_id' => $user['department_id']
        ];

        $this->taskModel->create($data);

        $_SESSION['success'] = "Task created successfully";

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

            $taskId = (int) ($_POST['task_id'] ?? 0);

            $status = $_POST['status'] ?? 'pending';

            if ($taskId <= 0) {

                $_SESSION['error'] = "Invalid task";

                header("Location: index.php?page=tasks");
                exit;
            }

            $this->taskModel->updateStatus($taskId, $status);

            $_SESSION['success'] = "Task updated";

            header("Location: index.php?page=tasks");
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE TASK
    |--------------------------------------------------------------------------
    */
    public function delete()
    {
        Auth::requireLogin();

        $user = Auth::user();

        $role = strtolower($user['role'] ?? 'staff');

        if ($role !== 'admin') {

            $_SESSION['error'] = "Access denied";

            header("Location: index.php?page=tasks");
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {

            $_SESSION['error'] = "Invalid task";

            header("Location: index.php?page=tasks");
            exit;
        }

        $this->taskModel->delete($id);

        $_SESSION['success'] = "Task deleted successfully";

        header("Location: index.php?page=tasks");
        exit;
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

            $taskId = (int) ($_POST['task_id'] ?? 0);

            $description = trim($_POST['description'] ?? '');

            $userId = Auth::user()['id'];

            if ($taskId <= 0) {

                $_SESSION['error'] = "Invalid task";

                header("Location: index.php?page=tasks");
                exit;
            }

            if ($description === '') {

                $_SESSION['error'] = "Comment required";

                header("Location: index.php?page=task_show&id=" . $taskId);
                exit;
            }

            $this->taskModel->addComment($taskId, $userId, $description);

            $_SESSION['success'] = "Comment added";

            header("Location: index.php?page=task_show&id=" . $taskId);
            exit;
        }
    }
}
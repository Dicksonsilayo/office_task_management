<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../configs/database.php';

require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Goal.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/TaskLogs.php';

class TaskController
{
    private $taskModel;

    public function __construct()
    {
        $this->taskModel = new Task();
    }

    /*
    |--------------------------------------------------------------------------
    | CENTRAL AUDIT LOGGER (CLEAN & REUSABLE)
    |--------------------------------------------------------------------------
    */
    private function audit($taskId, $userId, $action, $description)
    {
        $log = new TaskLog();
        $log->create($taskId, $userId, $action, $description);
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

        if ($id <= 0) {
           Flash::set('error','Invalid task ID');
            header("Location: index.php?page=tasks");
            exit;
        }

        if (!$this->taskModel->canAccessTask($id, $user)) {
            Flash::set('error','Access denied');
            header("Location: index.php?page=tasks");
            exit;
        }

        $task = $this->taskModel->getById($id);

        if (!$task) {
            $_SESSION['error'] = "Task not found";
            header("Location: index.php?page=tasks");
            exit;
        }

        $comments = $this->taskModel->getComments($id);
        $logs = $this->taskModel->getLogs($id);

        require __DIR__ . '/../views/tasks/show.php';
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

        if ($title === '') $errors[] = "Title is required";
        if ($description === '') $errors[] = "Description is required";
        if (empty($_POST['assigned_to'])) $errors[] = "Assign task to user";

        if (!empty($errors)) {
            Flash::set('errors','$errors');
            header("Location: index.php?page=create_task");
            exit;
        }

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

        $taskId = $this->taskModel->create($data);

        // AUDIT
       $this->audit($taskId, $user['id'], 'task_assigned', 'Task assigned to user ID ' . $data['assigned_to']);

        // NOTIFICATION
        (new Notification())->create(
            $data['assigned_to'],
            $taskId,
            "New task assigned: {$title}"
        );
Flash::set('success','Task created successfully');
        header("Location: index.php?page=tasks");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS (FULL AUDIT WORKFLOW)
    |--------------------------------------------------------------------------
    */
    public function updateStatus()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $user = Auth::user();

        $taskId = (int) ($_POST['task_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        $allowed = [
            'in_progress',
            'reviewed',
            'satisfied',
            'not_satisfied',
            'completed'
        ];

        if ($taskId <= 0 || !in_array($status, $allowed)) {
            Flash::set('error','Invalid request');
            header("Location: index.php?page=tasks");
            exit;
        }

        $this->taskModel->updateStatus($taskId, $status);
        $task = $this->taskModel->getById($taskId);

        // AUDIT
        $this->audit(
            $taskId,
            $user['id'],
            'status_changed',
            "Status updated to {$status}"
        );

        // NOTIFICATIONS (SMART FLOW)
        $notification = new Notification();

        $message = "Task '{$task['title']}' updated to {$status}";

        $notification->create($task['assigned_by'], $taskId, $message);

        if (in_array($status, ['satisfied', 'not_satisfied'])) {
            $notification->create($task['assigned_to'], $taskId, $message);
        }

      Flash::set('succes','task updated successfully');
        header("Location: index.php?page=task_show&id=" . $taskId);
        exit;
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
        $roles = $user['roles'] ?? [];

        if (is_string($roles)) {
            $roles = explode(',', strtolower($roles));
        }

        $roles = array_map('trim', $roles);

        if (!in_array('admin', $roles)) {
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

        $this->audit($id, $user['id'], 'task_deleted', 'Task removed from system');

        $this->taskModel->delete($id);

        $_SESSION['success'] = "Task deleted successfully";
        header("Location: index.php?page=tasks");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | ADD COMMENT + AUDIT
    |--------------------------------------------------------------------------
    */
    public function addComment()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $user = Auth::user();

        $taskId = (int) ($_POST['task_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        if ($taskId <= 0 || $description === '') {
            Flash::set('error','invalid comments');
            header("Location: index.php?page=tasks");
            exit;
        }

        $this->taskModel->addComment($taskId, $user['id'], $description);

        $this->audit($taskId, $user['id'], 'comment_added', 'Task review/comment added');
        Flash::set('success','comments added successfully');
        header("Location: index.php?page=task_show&id=" . $taskId);
        exit;
    }
}
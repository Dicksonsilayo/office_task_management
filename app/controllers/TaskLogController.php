<?php

require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/TaskLog.php';
require_once __DIR__ . '/../core/Auth.php';

class TaskLogController
{
    private $taskModel;
    private $taskLogModel;

    public function __construct()
    {
        $this->taskModel = new Task();
        $this->taskLogModel = new TaskLog();
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW TASK LOGS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        Auth::requireLogin();

        $taskId = $_GET['task_id'] ?? null;

        if (!$taskId) {
            die("Task ID is required");
        }

        $task = $this->taskModel->find($taskId);

        if (!$task) {
            die("Task not found");
        }

        $logs = $this->taskLogModel->getByTask($taskId);

        require __DIR__ . '/../views/tasks/logs.php';
    }

    /*
    |--------------------------------------------------------------------------
    | ADD TASK LOG
    |--------------------------------------------------------------------------
    */
    public function store()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $taskId = $_POST['task_id'] ?? null;
            $comment = trim($_POST['comment'] ?? '');

            if (!$taskId || $comment === '') {

                $_SESSION['error'] = "Comment is required";

                header("Location: index.php?page=task_logs&task_id=" . $taskId);
                exit;
            }

            $user = Auth::user();

            $this->taskLogModel->create([
                'task_id' => $taskId,
                'user_id' => $user['id'],
                'comment' => $comment
            ]);

            $_SESSION['success'] = "Task log added successfully";

            header("Location: index.php?page=task_logs&task_id=" . $taskId);
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE TASK LOG
    |--------------------------------------------------------------------------
    */
    public function delete()
    {
        Auth::requireLogin();

        $id = $_GET['id'] ?? null;
        $taskId = $_GET['task_id'] ?? null;

        if (!$id) {
            die("Log ID missing");
        }

        $user = Auth::user();

        // OPTIONAL SECURITY: only admin or owner can delete
        if (($user['is_head'] ?? 0) != 1) {
            die("Unauthorized action");
        }

        $this->taskLogModel->delete($id);

        $_SESSION['success'] = "Task log deleted";

        header("Location: index.php?page=task_logs&task_id=" . $taskId);
        exit;
    }
}
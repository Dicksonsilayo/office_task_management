<?php

// BASE PATH FIX
require_once __DIR__ . '/app/configs/database.php';
require_once __DIR__ . '/app/core/Auth.php';
require_once __DIR__ . '/app/models/Task.php';
require_once __DIR__ . '/app/models/Notification.php';
require_once __DIR__ . '/app/controllers/TaskController.php';

try {

    $taskController = new TaskController();
    $taskController->checkOverdueTasks();

    echo "Overdue task check completed\n";

} catch (Throwable $e) {

    echo "Error: " . $e->getMessage() . "\n";
}
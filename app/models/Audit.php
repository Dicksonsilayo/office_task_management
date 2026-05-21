<?php

class Audit
{
    private $taskLog;

    public function __construct()
    {
        $this->taskLog = new TaskLog();
    }

    public function log($taskId, $userId, $action, $message, $meta = null)
    {
        $this->taskLog->create(
            $taskId,
            $userId,
            $action,
            $message,
            $meta ? json_encode($meta) : null
        );
    }
}
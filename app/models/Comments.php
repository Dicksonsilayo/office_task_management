<?php

require_once __DIR__ . '/../configs/database.php';

class Comments {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

  
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
}
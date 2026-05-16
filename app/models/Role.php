<?php

error_reporting(E_ALL);
ini_set('display_errors',1);
require_once __DIR__ . '/../configs/database.php';

class Role {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    // GET ALL ROLES
    public function getAll() {

        $stmt = $this->conn->query("SELECT * FROM roles");
        return $stmt->fetch_all(MYSQLI_ASSOC);
    }

    // ASSIGN ROLE TO USER
    public function assignRole($userId, $roleId) {

        $stmt = $this->conn->prepare("
            INSERT INTO role_user (user_id, role_id)
            VALUES (?, ?)
        ");

        $stmt->bind_param("ii", $userId, $roleId);

        return $stmt->execute();
    }
}
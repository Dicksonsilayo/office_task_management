<?php

error_reporting(E_ALL);
ini_set('display_errors',1);
require_once __DIR__ . '/../configs/database.php';

class Department {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    // =========================
    // GET ALL DEPARTMENTS
    // =========================
    public function getAll() {

        $sql = "SELECT * FROM departments ORDER BY id DESC";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // =========================
    // GET SINGLE DEPARTMENT
    // =========================
    public function findById($id) {

        $stmt = $this->conn->prepare("
            SELECT * FROM departments WHERE id = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // =========================
    // CREATE DEPARTMENT
    // =========================
    public function create($data) {

        $stmt = $this->conn->prepare("
            INSERT INTO departments (name, user_id)
            VALUES (?, ?)
        ");

        $stmt->bind_param(
            "si",
            $data['name'],
            $data['user_id']
        );

        return $stmt->execute();
    }

    // =========================
    // UPDATE DEPARTMENT
    // =========================
    public function update($data) {

        $stmt = $this->conn->prepare("
            UPDATE departments
            SET name = ?, user_id = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sii",
            $data['name'],
            $data['user_id'],
            $data['id']
        );

        return $stmt->execute();
    }

    // =========================
    // DELETE DEPARTMENT
    // =========================
    public function delete($id) {

        $stmt = $this->conn->prepare("
            DELETE FROM departments WHERE id = ?
        ");

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
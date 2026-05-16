

<?php

require_once __DIR__ . '/../configs/database.php';

class Goal {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL GOALS
    |--------------------------------------------------------------------------
    */
    public function getAll() {

        $result = $this->conn->query("
            SELECT * FROM goals ORDER BY id DESC
        ");

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND GOAL BY ID
    |--------------------------------------------------------------------------
    */
    public function find($id) {

        $stmt = $this->conn->prepare("
            SELECT * FROM goals WHERE id = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE GOAL
    |--------------------------------------------------------------------------
    */
    public function create($data) {

        $stmt = $this->conn->prepare("
            INSERT INTO goals (name, description, department_id)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "ssi",
            $data['name'],
            $data['description'],
            $data['department_id']
        );

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE GOAL
    |--------------------------------------------------------------------------
    */
    public function update($data) {

        $stmt = $this->conn->prepare("
            UPDATE goals
            SET name = ?, description = ?, department_id = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssii",
            $data['name'],
            $data['description'],
            $data['department_id'],
            $data['id']
        );

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE GOAL
    |--------------------------------------------------------------------------
    */
    public function delete($id) {

        $stmt = $this->conn->prepare("
            DELETE FROM goals WHERE id = ?
        ");

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}







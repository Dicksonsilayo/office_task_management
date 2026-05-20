<?php

require_once __DIR__ . '/../configs/database.php';

class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function findByEmail($email)
{
    $stmt = $this->conn->prepare("
        SELECT 
            users.*
        FROM users
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS (WITH ROLES + DEPARTMENT)
    |--------------------------------------------------------------------------
    */
    public function getAll()
    {
        $stmt = $this->conn->query("
            SELECT 
                users.*,
                departments.name AS department_name,
                GROUP_CONCAT(roles.name SEPARATOR ', ') AS roles
            FROM users

            LEFT JOIN departments 
                ON departments.id = users.department_id

            LEFT JOIN role_user 
                ON role_user.user_id = users.id

            LEFT JOIN roles 
                ON roles.id = role_user.role_id

            GROUP BY users.id

            ORDER BY users.id DESC
        ");

        return $stmt->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */
    public function create($data)
    {
        // 1. Insert user
        $stmt = $this->conn->prepare("
            INSERT INTO users (name, email, password, department_id)
            VALUES (?, ?, ?, ?)
        ");

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt->bind_param(
            "sssi",
            $data['name'],
            $data['email'],
            $hashedPassword,
            $data['department_id']
        );

        $stmt->execute();

        $userId = $this->conn->insert_id;

        // 2. Insert role into pivot
        $this->conn->query("
            INSERT INTO role_user (user_id, role_id)
            VALUES ($userId, {$data['role_id']})
        ");

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */
    public function update($data)
    {
        $id = $data['id'];

        if (!empty($data['password'])) {

            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

            $stmt = $this->conn->prepare("
                UPDATE users 
                SET name=?, email=?, password=?, department_id=?
                WHERE id=?
            ");

            $stmt->bind_param(
                "sssii",
                $data['name'],
                $data['email'],
                $hashedPassword,
                $data['department_id'],
                $id
            );

        } else {

            $stmt = $this->conn->prepare("
                UPDATE users 
                SET name=?, email=?, department_id=?
                WHERE id=?
            ");

            $stmt->bind_param(
                "ssii",
                $data['name'],
                $data['email'],
                $data['department_id'],
                $id
            );
        }

        $stmt->execute();

        // 2. Reset roles
        $this->conn->query("DELETE FROM role_user WHERE user_id = $id");

        // 3. Re-assign role
        if (!empty($data['role_id'])) {
            $this->conn->query("
                INSERT INTO role_user (user_id, role_id)
                VALUES ($id, {$data['role_id']})
            ");
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $this->conn->query("DELETE FROM users WHERE id = $id");
    }

    /*
    |--------------------------------------------------------------------------
    | FIND USER
    |--------------------------------------------------------------------------
    */
    public function find($id)
    {
        $stmt = $this->conn->query("
            SELECT * FROM users WHERE id = $id LIMIT 1
        ");

        return $stmt->fetch_assoc();
    }
}
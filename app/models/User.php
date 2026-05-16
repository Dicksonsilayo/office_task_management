<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
require_once __DIR__ . '/../configs/database.php';

class User {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND USER BY EMAIL (WITH ROLE)
    |--------------------------------------------------------------------------
    */public function findByEmail($email)
{
    $stmt = $this->conn->prepare("
        SELECT 
            users.*,
            roles.name AS role
        FROM users

        LEFT JOIN role_user
            ON role_user.user_id = users.id

        LEFT JOIN roles
            ON roles.id = role_user.role_id

        WHERE users.email = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $email);

    $stmt->execute();

    return $stmt
        ->get_result()
        ->fetch_assoc();
}
    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */
  public function getAll()
{
    $result = $this->conn->query("
        SELECT 
            users.id,
            users.name,
            users.email,
            users.department_id,
            roles.name AS role
        FROM users
        LEFT JOIN role_user ON role_user.user_id = users.id
        LEFT JOIN roles ON roles.id = role_user.role_id
        GROUP BY users.id
    ");

    return $result->fetch_all(MYSQLI_ASSOC);
}    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */public function create($data) {

    /*
    |--------------------------------------------------------------------------
    | HASH PASSWORD
    |--------------------------------------------------------------------------
    */

    $password = password_hash(
        $data['password'],
        PASSWORD_DEFAULT
    );

    /*
    |--------------------------------------------------------------------------
    | INSERT USER
    |--------------------------------------------------------------------------
    */

    $stmt = $this->conn->prepare("
        INSERT INTO users (
            name,
            email,
            password,
            department_id
        )
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssi",
        $data['name'],
        $data['email'],
        $password,
        $data['department_id']
    );

    $stmt->execute();

    /*
    |--------------------------------------------------------------------------
    | GET NEW USER ID
    |--------------------------------------------------------------------------
    */

    $userId = $this->conn->insert_id;

    /*
    |--------------------------------------------------------------------------
    | ASSIGN ROLE
    |--------------------------------------------------------------------------
    */

    $roleStmt = $this->conn->prepare("
        INSERT INTO role_user (
            role_id,
            user_id
        )
        VALUES (?, ?)
    ");

    $roleStmt->bind_param(
        "ii",
        $data['role_id'],
        $userId
    );

    return $roleStmt->execute();
}
/*
|--------------------------------------------------------------------------
| FIND USER BY ID
|--------------------------------------------------------------------------
*/public function find($id)
{
    $stmt = $this->conn->prepare("
        SELECT 
            users.*,
            roles.name AS role
        FROM users
        LEFT JOIN role_user ON role_user.user_id = users.id
        LEFT JOIN roles ON roles.id = role_user.role_id
        WHERE users.id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function update($data)
{
    // UPDATE USER TABLE
    $stmt = $this->conn->prepare("
        UPDATE users
        SET name = ?, email = ?, department_id = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssii",
        $data['name'],
        $data['email'],
        $data['department_id'],
        $data['id']
    );

    $stmt->execute();

    // PASSWORD UPDATE (OPTIONAL)
    if (!empty($data['password'])) {

        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            UPDATE users
            SET password = ?
            WHERE id = ?
        ");

        $stmt->bind_param("si", $password, $data['id']);
        $stmt->execute();
    }

    // ROLE UPDATE
    $this->conn->query("DELETE FROM role_user WHERE user_id = {$data['id']}");

    $stmt = $this->conn->prepare("
        INSERT INTO role_user (role_id, user_id)
        VALUES (?, ?)
    ");

    $stmt->bind_param("ii", $data['role_id'], $data['id']);
    return $stmt->execute();
}
/*
|--------------------------------------------------------------------------
| DELETE USER
|--------------------------------------------------------------------------
*/public function delete($id)
{
    $stmt = $this->conn->prepare("
        DELETE FROM role_user
        WHERE user_id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt = $this->conn->prepare("
        DELETE FROM users
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}
public function getByDepartment($deptId) {

    $stmt = $this->conn->prepare("
        SELECT * FROM users
        WHERE department_id = ?
    ");

    $stmt->bind_param("i", $deptId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
public function getAllByRole($user)
{
    $role = strtolower($user['role_name'] ?? '');
    $userId = $user['id'];
    $deptId = $user['department_id'] ?? 0;

    if ($role === 'admin') {
        $result = $this->conn->query("SELECT * FROM tasks ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    if ($role === 'hod') {

        $stmt = $this->conn->prepare("
            SELECT * FROM tasks 
            WHERE department_id = ?
            ORDER BY id DESC
        ");

        $stmt->bind_param("i", $deptId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // staff
    $stmt = $this->conn->prepare("
        SELECT * FROM tasks 
        WHERE assigned_to = ?
        ORDER BY id DESC
    ");

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
}
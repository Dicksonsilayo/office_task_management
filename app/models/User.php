<?php

require_once __DIR__ . '/../configs/database.php';

class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS WITH ROLE + DEPARTMENT
    |--------------------------------------------------------------------------
    */
    public function getAll()
{
    $sql = "
        SELECT 
            u.*,
            d.name AS department_name
        FROM users u
        LEFT JOIN departments d ON d.id = u.department_id
        ORDER BY u.id DESC
    ";

    $result = $this->conn->query($sql);

    return $result->fetch_all(MYSQLI_ASSOC);
}
    /*
    |--------------------------------------------------------------------------
    | FIND USER BY ID
    |--------------------------------------------------------------------------
    */
    public function find($id)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                users.*,
                roles.id AS role_id,
                roles.name AS role

            FROM users

            LEFT JOIN role_user
                ON role_user.user_id = users.id

            LEFT JOIN roles
                ON roles.id = role_user.role_id

            WHERE users.id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY EMAIL (LOGIN)
    |--------------------------------------------------------------------------
    */
    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("
            SELECT 
                users.*,

                GROUP_CONCAT(roles.name) AS roles

            FROM users

            LEFT JOIN role_user
                ON role_user.user_id = users.id

            LEFT JOIN roles
                ON roles.id = role_user.role_id

            WHERE users.email = ?

            GROUP BY users.id

            LIMIT 1
        ");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */
    public function create($data)
    {
        /*
        |--------------------------------------------------------------------------
        | INSERT USER
        |--------------------------------------------------------------------------
        */
        $hashedPassword = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        $stmt = $this->conn->prepare("
            INSERT INTO users(
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
            $hashedPassword,
            $data['department_id']
        );

        $stmt->execute();

        $userId = $this->conn->insert_id;

        /*
        |--------------------------------------------------------------------------
        | INSERT ROLE INTO PIVOT TABLE
        |--------------------------------------------------------------------------
        */
        $roleStmt = $this->conn->prepare("
            INSERT INTO role_user(
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
    | UPDATE USER
    |--------------------------------------------------------------------------
    */
    public function update($data)
    {
        /*
        |--------------------------------------------------------------------------
        | UPDATE USERS TABLE
        |--------------------------------------------------------------------------
        */
        if (!empty($data['password'])) {

            $hashedPassword = password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            );

            $stmt = $this->conn->prepare("
                UPDATE users
                SET
                    name = ?,
                    email = ?,
                    password = ?,
                    department_id = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "sssii",
                $data['name'],
                $data['email'],
                $hashedPassword,
                $data['department_id'],
                $data['id']
            );

        } else {

            $stmt = $this->conn->prepare("
                UPDATE users
                SET
                    name = ?,
                    email = ?,
                    department_id = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "ssii",
                $data['name'],
                $data['email'],
                $data['department_id'],
                $data['id']
            );
        }

        $stmt->execute();

        /*
        |--------------------------------------------------------------------------
        | UPDATE ROLE PIVOT
        |--------------------------------------------------------------------------
        */
        $deleteStmt = $this->conn->prepare("
            DELETE FROM role_user
            WHERE user_id = ?
        ");

        $deleteStmt->bind_param(
            "i",
            $data['id']
        );

        $deleteStmt->execute();

        $insertStmt = $this->conn->prepare("
            INSERT INTO role_user(
                role_id,
                user_id
            )
            VALUES (?, ?)
        ");

        $insertStmt->bind_param(
            "ii",
            $data['role_id'],
            $data['id']
        );

        return $insertStmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        /*
        |--------------------------------------------------------------------------
        | DELETE PIVOT FIRST
        |--------------------------------------------------------------------------
        */
        $pivotStmt = $this->conn->prepare("
            DELETE FROM role_user
            WHERE user_id = ?
        ");

        $pivotStmt->bind_param("i", $id);

        $pivotStmt->execute();

        /*
        |--------------------------------------------------------------------------
        | DELETE USER
        |--------------------------------------------------------------------------
        */
        $stmt = $this->conn->prepare("
            DELETE FROM users
            WHERE id = ?
        ");

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
} 
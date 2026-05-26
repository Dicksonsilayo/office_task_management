<?php

require_once __DIR__ . '/../configs/database.php';

class Visitor
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function autoCheckout()
    {
        $stmt = $this->conn->prepare("
            UPDATE visitor_attendance
            SET check_out = NOW()
            WHERE check_out IS NULL
            AND check_in <= (NOW() - INTERVAL 12 HOUR)
        ");

        return $stmt->execute();
    }
    public function getAll()
{
    $query = "
        SELECT *
        FROM visitors
        ORDER BY created_at DESC
    ";

    $result = $this->conn->query($query);

    return $result->fetch_all(MYSQLI_ASSOC);
}
public function getToday()
{
    $stmt = $this->conn->query("
        SELECT *
        FROM visitors
        WHERE DATE(created_at) = CURDATE()
        ORDER BY created_at DESC
    ");

    return $stmt->fetch_all(MYSQLI_ASSOC);
}

    /*
    |--------------------------------------------------------------------------
    | GET ALL VISITORS WITH STATUS
    |--------------------------------------------------------------------------
    */

    public function getAllWithStatus()
    {
        $query = "
            SELECT
                v.*,

                CASE
                    WHEN va.check_out IS NULL
                         AND va.id IS NOT NULL
                    THEN 'inside'
                    ELSE 'outside'
                END AS status

            FROM visitors v

            LEFT JOIN visitor_attendance va
                ON va.id = (
                    SELECT id
                    FROM visitor_attendance
                    WHERE visitor_id = v.id
                    ORDER BY id DESC
                    LIMIT 1
                )

            ORDER BY v.id DESC
        ";

        $result = $this->conn->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE VISITOR
    |--------------------------------------------------------------------------
    */

    public function create($data)
    {
        /*
        | Prevent duplicates using phone + name
        */

        $check = $this->conn->prepare("
            SELECT id
            FROM visitors
            WHERE phone = ?
            AND full_name = ?
            LIMIT 1
        ");

        $check->bind_param(
            "ss",
            $data['phone'],
            $data['full_name']
        );

        $check->execute();

        $exists = $check->get_result();

        if ($exists->num_rows > 0) {
            return false;
        }

        $stmt = $this->conn->prepare("
            INSERT INTO visitors(full_name, phone, purpose)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "sss",
            $data['full_name'],
            $data['phone'],
            $data['purpose']
        );

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IF VISITOR INSIDE
    |--------------------------------------------------------------------------
    */

    public function isInside($visitorId)
    {
        $stmt = $this->conn->prepare("
            SELECT id
            FROM visitor_attendance
            WHERE visitor_id = ?
            AND check_out IS NULL
            LIMIT 1
        ");

        $stmt->bind_param("i", $visitorId);

        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IN
    |--------------------------------------------------------------------------
    */

    public function checkIn($visitorId)
    {
        if ($this->isInside($visitorId)) {
            return false;
        }

        $stmt = $this->conn->prepare("
            INSERT INTO visitor_attendance(visitor_id, check_in)
            VALUES(?, NOW())
        ");

        $stmt->bind_param("i", $visitorId);

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK OUT
    |--------------------------------------------------------------------------
    */

    public function checkOut($visitorId)
    {
        $stmt = $this->conn->prepare("
            UPDATE visitor_attendance
            SET check_out = NOW()
            WHERE visitor_id = ?
            AND check_out IS NULL
        ");

        $stmt->bind_param("i", $visitorId);

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE HISTORY
    |--------------------------------------------------------------------------
    */

    public function attendanceHistory()
    {
        $query = "
            SELECT
                va.*,
                v.full_name,
                v.phone,
                v.purpose

            FROM visitor_attendance va

            INNER JOIN visitors v
                ON v.id = va.visitor_id

            ORDER BY va.id DESC
        ";

        $result = $this->conn->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
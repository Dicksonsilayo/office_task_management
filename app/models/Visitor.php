<?php

require_once __DIR__ . '/../configs/database.php';

class Visitor {

    private $conn;

    public function __construct() {

        $this->conn = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL VISITORS
    |--------------------------------------------------------------------------
    */
    public function getAll() {

        $result = $this->conn->query("
            SELECT *
            FROM visitors
            ORDER BY id DESC
        ");

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE VISITOR
    |--------------------------------------------------------------------------
    */
    public function create($data) {

        $stmt = $this->conn->prepare("
            INSERT INTO visitors(full_name, phone, purpose)
            VALUES(?, ?, ?)
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
    | CHECK IN
    |--------------------------------------------------------------------------
    */
    public function checkIn($visitorId) {

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
    public function checkOut($visitorId) {

        $stmt = $this->conn->prepare("
            UPDATE visitor_attendance
            SET check_out = NOW()
            WHERE visitor_id = ?
            AND check_out IS NULL
        ");

        $stmt->bind_param("i", $visitorId);

        return $stmt->execute();
    }

    public function isInside($visitorId) {

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
    | ATTENDANCE HISTORY
    |--------------------------------------------------------------------------
    */
    public function attendanceHistory() {

        $result = $this->conn->query("
            SELECT
                visitor_attendance.*,
                visitors.full_name,
                visitors.phone,
                visitors.purpose
            FROM visitor_attendance

            JOIN visitors
            ON visitors.id = visitor_attendance.visitor_id

            ORDER BY visitor_attendance.id DESC
        ");
    }
    public function getAllWithStatus()
{
    $query = "
        SELECT 
            v.*,

            CASE
                WHEN va.check_out IS NULL 
                     AND va.check_in IS NOT NULL
                THEN 'inside'
                ELSE 'outside'
            END AS status

        FROM visitors v

        LEFT JOIN visitor_attendance va
            ON va.visitor_id = v.id

        LEFT JOIN (
            SELECT visitor_id, MAX(id) as latest_id
            FROM visitor_attendance
            GROUP BY visitor_id
        ) latest
            ON latest.latest_id = va.id

        ORDER BY v.id DESC
    ";

    $result = $this->conn->query($query);

    return $result->fetch_all(MYSQLI_ASSOC);
}
    }
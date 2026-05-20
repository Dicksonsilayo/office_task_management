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
    | GET ALL VISITORS
    |--------------------------------------------------------------------------
    */
    public function getAll()
    {
        $stmt = $this->conn->query("
            SELECT 
                visitors.*,
                users.name AS visitor_name
            FROM visitors
            LEFT JOIN users ON users.id = visitors.user_id
            ORDER BY visitors.created_at DESC
        ");

        return $stmt->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET TODAY VISITORS
    |--------------------------------------------------------------------------
    */
    public function getToday()
    {
        $stmt = $this->conn->query("
            SELECT 
                visitors.*,
                users.name AS visitor_name
            FROM visitors
            LEFT JOIN users ON users.id = visitors.user_id
            WHERE DATE(visitors.created_at) = CURDATE()
            ORDER BY visitors.created_at DESC
        ");

        return $stmt->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE VISITOR
    |--------------------------------------------------------------------------
    */
    public function create($data)
    {
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
    | CHECK IN (SAFE - PREVENT MULTIPLE ACTIVE SESSIONS)
    |--------------------------------------------------------------------------
    */
    public function checkIn($visitorId)
    {
        // prevent duplicate check-in
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
    | IS INSIDE
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
    | AUTO CHECKOUT AFTER 12 HOURS (NEW FEATURE)
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

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE HISTORY (FIXED - WAS MISSING RETURN)
    |--------------------------------------------------------------------------
    */
    public function attendanceHistory()
    {
        $result = $this->conn->query("
            SELECT
                va.*,
                v.full_name,
                v.phone,
                v.purpose
            FROM visitor_attendance va
            JOIN visitors v ON v.id = va.visitor_id
            ORDER BY va.id DESC
        ");

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL WITH STATUS (CLEAN VERSION)
    |--------------------------------------------------------------------------
    */
    public function getAllWithStatus()
    {
        $result = $this->conn->query("
            SELECT 
                v.*,
                va.check_in,
                va.check_out,
                CASE
                    WHEN va.check_out IS NULL AND va.check_in IS NOT NULL
                    THEN 'inside'
                    ELSE 'outside'
                END AS status
            FROM visitors v
            LEFT JOIN visitor_attendance va 
                ON va.id = (
                    SELECT MAX(id)
                    FROM visitor_attendance
                    WHERE visitor_id = v.id
                )
            ORDER BY v.id DESC
        ");

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
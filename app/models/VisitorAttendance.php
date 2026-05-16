<?php

require_once __DIR__ . '/../configs/database.php';

class VisitorAttendance {

    private $conn;

    public function __construct() {

        $this->conn = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK-IN
    |--------------------------------------------------------------------------
    */
    public function checkIn($visitorId) {

        // prevent duplicate active checkin
        $stmt = $this->conn->prepare("
            SELECT id
            FROM visitor_attendance
            WHERE visitor_id = ?
            AND check_out IS NULL
        ");

        $stmt->bind_param("i", $visitorId);
        $stmt->execute();

        $existing = $stmt->get_result()->fetch_assoc();

        if ($existing) {
            return false;
        }

        $stmt = $this->conn->prepare("
            INSERT INTO visitor_attendance (
                visitor_id,
                check_in
            )
            VALUES (?, NOW())
        ");

        $stmt->bind_param("i", $visitorId);

        return $stmt->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK-OUT
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

    /*
    |--------------------------------------------------------------------------
    | VISITOR STATUS
    |--------------------------------------------------------------------------
    */
    public function getStatus($visitorId) {

        $stmt = $this->conn->prepare("
            SELECT *
            FROM visitor_attendance
            WHERE visitor_id = ?
            AND check_out IS NULL
            LIMIT 1
        ");

        $stmt->bind_param("i", $visitorId);
        $stmt->execute();

        $attendance = $stmt->get_result()->fetch_assoc();

        return $attendance ? 'inside' : 'outside';
    }
    public function autoCheckoutExpired($minutes = 720) {

    $stmt = $this->conn->prepare("
        UPDATE visitor_attendance
        SET check_out = NOW()
        WHERE check_out IS NULL
        AND TIMESTAMPDIFF(MINUTE, check_in, NOW()) > ?
    ");

    $stmt->bind_param("i", $minutes);
    $stmt->execute();
}

public function getStats() {

    $inside = $this->conn->query("
        SELECT COUNT(*) as total FROM visitor_attendance WHERE check_out IS NULL
    ")->fetch_assoc();

    $outside = $this->conn->query("
        SELECT COUNT(*) as total FROM visitor_attendance WHERE check_out IS NOT NULL
    ")->fetch_assoc();

    return [
        'inside' => $inside['total'],
        'outside' => $outside['total']
    ];
}
}
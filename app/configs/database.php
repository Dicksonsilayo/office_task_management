<?php

class Database {

    private $host = "localhost";
    private $user = "root";
    private $password = "dickson@123";
    private $dbname = "office_task_and_visitor_management";

    private $conn;

    public function connect() {

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->dbname
        );

        $this->conn->set_charset("utf8mb4");

        return $this->conn;
    }
}
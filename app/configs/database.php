  <?php

class Database {

    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "dickson@123";
    private $dbname = "office_task_and_visitor_management";

    public $conn;

    public function connect() {

        try {

            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $this->conn = new mysqli(
                $this->host,
                $this->user,
                $this->password,
                $this->dbname
            );

            $this->conn->set_charset("utf8mb4");

            return $this->conn;

        } catch (mysqli_sql_exception $e) {

            die("
                <div style='
                    padding:20px;
                    margin:40px auto;
                    width:500px;
                    background:#ffe6e6;
                    border:1px solid #ff0000;
                    border-radius:8px;
                    font-family:Arial;
                '>

                    <h2 style='color:red;'>Database Connection Error</h2>

                    <p>
                        Unable to connect to MySQL/MariaDB server.
                    </p>

                    <p>
                        Please make sure:
                    </p>

                    <ul>
                        <li>MySQL/MariaDB service is running</li>
                        <li>Database exists</li>
                        <li>Username and password are correct</li>
                    </ul>

                    <hr>

                    <small>
                        Error: {$e->getMessage()}
                    </small>

                </div>
            ");
        }
    }
}
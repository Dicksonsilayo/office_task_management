<?php

error_reporting(E_ALL);
ini_set('display_errors',1);
require_once __DIR__ . '/../core/Model.php';

class Dashboard extends Model {

    public function totalVisitors(){

        $query = "SELECT COUNT(*) as total FROM visitors";

        $result = $this->db->query($query);

        return $result->fetch_assoc()['total'];
    }

    public function totalTasks(){

        $query = "SELECT COUNT(*) as total FROM tasks";

        $result = $this->db->query($query);

        return $result->fetch_assoc()['total'];
    }

    public function totalUsers(){

        $query = "SELECT COUNT(*) as total FROM users";

        $result = $this->db->query($query);

        return $result->fetch_assoc()['total'];
    }
}
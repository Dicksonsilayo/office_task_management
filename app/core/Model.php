<?php

error_reporting(E_ALL);
ini_set('display_errors',1);
require_once __DIR__ . '/../configs/database.php';

class Model {

    protected $db;

    public function __construct(){

        $database = new Database();

        $this->db = $database->connect();
    }
}
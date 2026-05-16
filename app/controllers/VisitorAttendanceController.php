<?php

error_reporting(E_ALL);
ini_set('display_errors',1);
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/VisitorAttendance.php';

class VisitorAttendanceController {

    private $model;

    public function __construct() {
        $this->model = new VisitorAttendance();
    }

    public function checkIn() {

        Auth::requireLogin();

        if (!Auth::isReceptionist()) {
            header("Location: index.php?page=dashboard");
            exit;
        }

        $this->model->checkIn($_POST['visitor_id']);

        header("Location: index.php?page=visitors");
    }

    public function checkOut() {

        Auth::requireLogin();

        if (!Auth::isReceptionist()) {
            header("Location: index.php?page=dashboard");
            exit;
        }

        $this->model->checkOut($_POST['visitor_id']);

        header("Location: index.php?page=visitors");
    }
}
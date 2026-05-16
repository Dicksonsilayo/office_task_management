<?php

require_once __DIR__ . '/../models/Visitor.php';
require_once __DIR__ . '/../core/Auth.php';

class VisitorController {

    private $visitorModel;

    public function __construct() {
        $this->visitorModel = new Visitor();
    }

    /*
    |--------------------------------------------------------------------------
    | LIST VISITORS
    |--------------------------------------------------------------------------
    */
    public function index() {

    Auth::requireLogin();
    
$visitors = $this->visitorModel->getAllWithStatus();

    // AJAX REQUEST (return only table rows)
    if (isset($_GET['ajax'])) {
        require __DIR__ . '/../views/visitors/partials/row.php';
        exit;
    }

    require __DIR__ . '/../views/visitors/index.php';
}

    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */
    public function create() {

        Auth::requireLogin();

        require __DIR__ . '/../views/visitors/create.php';
    }

    /*
    |--------------------------------------------------------------------------
    | STORE VISITOR (FIXED VALIDATION)
    |--------------------------------------------------------------------------
    */
    public function store() {

        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);

            // strict validation
            if (strlen($phone) < 10 || strlen($phone) > 13) {
                $_SESSION['error'] = "Invalid phone number";
                header("Location: index.php?page=create_visitor");
                exit;
            }

            $data = [
                'full_name' => trim($_POST['full_name']),
                'phone'     => $phone,
                'purpose'   => trim($_POST['purpose'])
            ];

            $this->visitorModel->create($data);

            header("Location: index.php?page=visitors");
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IN (SAFE + NO DUPLICATES)
    |--------------------------------------------------------------------------
    */
    public function checkIn() {

        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $visitorId = $_POST['visitor_id'];

            // prevent double check-in
            if ($this->visitorModel->isInside($visitorId)) {
                header("Location: index.php?page=visitors");
                exit;
            }

            $this->visitorModel->checkIn($visitorId);

            header("Location: index.php?page=visitors");
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK OUT (SAFE)
    |--------------------------------------------------------------------------
    */
    public function checkOut() {

        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $visitorId = $_POST['visitor_id'];

            $this->visitorModel->checkOut($visitorId);

            header("Location: index.php?page=visitors");
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */
    public function history() {

        Auth::requireLogin();

        $history = $this->visitorModel->attendanceHistory();

        require __DIR__ . '/../views/visitors/history.php';
    }
}
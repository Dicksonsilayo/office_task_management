<?php

require_once __DIR__ . '/../models/Visitor.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Flash.php';

class VisitorController
{
    private $visitorModel;

    public function __construct()
    {
        $this->visitorModel = new Visitor();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        Auth::requireLogin();

        $this->visitorModel->autoCheckout();

        $visitors = $this->visitorModel->getAllWithStatus();

        require __DIR__ . '/../views/visitors/index.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE PAGE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        Auth::requireLogin();

        require __DIR__ . '/../views/visitors/create.php';
    }

    /*
    |--------------------------------------------------------------------------
    | STORE VISITOR
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=visitors");
            exit;
        }

        $full_name = trim($_POST['full_name']);
        $phone     = preg_replace('/[^0-9]/', '', $_POST['phone']);
        $purpose   = trim($_POST['purpose']);

        if (empty($full_name) || empty($phone) || empty($purpose)) {

            Flash::set('error', 'All fields are required');

            header("Location: index.php?page=create_visitor");
            exit;
        }

        if (strlen($phone) < 10 || strlen($phone) > 13) {

            Flash::set('error', 'Invalid phone number');

            header("Location: index.php?page=create_visitor");
            exit;
        }

        $created = $this->visitorModel->create([
            'full_name' => $full_name,
            'phone' => $phone,
            'purpose' => $purpose
        ]);

        if (!$created) {

            Flash::set('error', 'Visitor already exists');

            header("Location: index.php?page=create_visitor");
            exit;
        }

        Flash::set('success', 'Visitor registered successfully');

        header("Location: index.php?page=visitors");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IN
    |--------------------------------------------------------------------------
    */

    public function checkIn()
    {
        Auth::requireLogin();

        $visitorId = (int) $_POST['visitor_id'];

        if ($this->visitorModel->isInside($visitorId)) {

            Flash::set('error', 'Visitor already inside');

            header("Location: index.php?page=visitors");
            exit;
        }

        $this->visitorModel->checkIn($visitorId);

        Flash::set('success', 'Visitor checked in successfully');

        header("Location: index.php?page=visitors");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK OUT
    |--------------------------------------------------------------------------
    */

    public function checkOut()
    {
        Auth::requireLogin();

        $visitorId = (int) $_POST['visitor_id'];

        $this->visitorModel->checkOut($visitorId);

        Flash::set('success', 'Visitor checked out successfully');

        header("Location: index.php?page=visitors");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        Auth::requireLogin();

        $history = $this->visitorModel->attendanceHistory();

        require __DIR__ . '/../views/visitors/history.php';
    }
}
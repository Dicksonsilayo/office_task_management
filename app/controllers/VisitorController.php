<?php

require_once __DIR__ . '/../models/Visitor.php';
require_once __DIR__ . '/../core/Auth.php';

class VisitorController
{
    private $visitorModel;

    public function __construct()
    {
        $this->visitorModel = new Visitor();
    }

    public function index()
    {
        Auth::requireLogin();

        // AUTO CHECKOUT (important)
        $this->visitorModel->autoCheckout();

        $visitors = $this->visitorModel->getAllWithStatus();

        if (isset($_GET['ajax'])) {
            require __DIR__ . '/../views/visitors/partials/row.php';
            exit;
        }

        require __DIR__ . '/../views/visitors/index.php';
    }

    public function create()
    {
        Auth::requireLogin();
        require __DIR__ . '/../views/visitors/create.php';
    }

    public function store()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $full_name = trim($_POST['full_name']);
            $phone     = preg_replace('/[^0-9]/', '', $_POST['phone']);
            $purpose   = trim($_POST['purpose']);

            if (strlen($full_name) > 150) {
                $_SESSION['error'] = "Name too long";
                header("Location: index.php?page=create_visitor");
                exit;
            }

            if (strlen($purpose) > 150) {
                $_SESSION['error'] = "Purpose too long";
                header("Location: index.php?page=create_visitor");
                exit;
            }

            if (strlen($phone) < 10 || strlen($phone) > 13) {
                $_SESSION['error'] = "Invalid phone number";
                header("Location: index.php?page=create_visitor");
                exit;
            }

            $this->visitorModel->create([
                'full_name' => $full_name,
                'phone'     => $phone,
                'purpose'   => $purpose
            ]);

            $_SESSION['success'] = "Visitor added successfully";
            header("Location: index.php?page=visitors");
            exit;
        }
    }

    public function checkIn()
{
    Auth::requireLogin();

    $visitorId = (int) $_POST['visitor_id'];

    if ($this->visitorModel->isInside($visitorId)) {
        $_SESSION['error'] = "Visitor already inside";
        header("Location: index.php?page=visitors");
        exit;
    }

    $this->visitorModel->checkIn($visitorId);

    $_SESSION['success'] = "Visitor checked in";
    header("Location: index.php?page=visitors");
    exit;
}

public function checkOut()
{
    Auth::requireLogin();

    $visitorId = (int) $_POST['visitor_id'];

    $this->visitorModel->checkOut($visitorId);

    $_SESSION['success'] = "Visitor checked out";
    header("Location: index.php?page=visitors");
    exit;
}
 
    public function history()
    {
        Auth::requireLogin();

        $history = $this->visitorModel->attendanceHistory();

        require __DIR__ . '/../views/visitors/history.php';
    }
}
<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationController {

    private $model;

    public function __construct() {
        $this->model = new Notification();
    }

    public function index() {

        Auth::requireLogin();

        $user = Auth::user();

        $notifications = $this->model->getByUser($user['id']);

        require __DIR__ . '/../views/notifications/index.php';
    }

    public function markRead() {

        Auth::requireLogin();

        $id = $_GET['id'] ?? null;

        if ($id) {
            $this->model->markAsRead($id);
        }

        header("Location: index.php?page=notifications");
        exit;
    }

    public function markAllRead() {

        Auth::requireLogin();

        $user = Auth::user();

        $this->model->markAllRead($user['id']);

        header("Location: index.php?page=notifications");
        exit;
    }
       public function read()
    {
        Auth::requireLogin();

        if (isset($_GET['id'])) {

            $this->model
                ->markAsRead($_GET['id']);
        }

        header("Location: index.php?page=notifications");
        exit;
    }

}
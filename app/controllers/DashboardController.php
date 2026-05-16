<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/Visitor.php';

class DashboardController
{
    public function index()
    {
        Auth::requireLogin();

        $authUser = Auth::user();

        $userModel = new User();
        $taskModel = new Task();
        $visitorModel = new Visitor();

        $data = [
            'user' => $authUser,
            'totalUsers' => count($userModel->getAll()),
            'totalTasks' => count($taskModel->getAllByRole($authUser)),
            'totalVisitors' => count($visitorModel->getAll()),
        ];

        // ROLE-BASED DASHBOARD ROUTING
        if ($authUser['role'] === 'admin') {
            require __DIR__ . '/../views/dashboard/index.php';
        }

        elseif ($authUser['role'] === 'hod') {
            require __DIR__ . '/../views/dashboard/hod.php';
        }

        elseif ($authUser['role'] === 'receptionist') {
            require __DIR__ . '/../views/dashboard/receptionist.php';
        }

        else {
            require __DIR__ . '/../views/dashboard/staff.php';
        }
    }
}
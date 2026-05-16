<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController {

    public function index()
    {
        Auth::requireLogin();

        $authUser = Auth::user();
        $role = strtolower($authUser['role'] ?? 'staff');

        $dashboard = new Dashboard();

        $data = [
            'user' => $authUser,
            'totalUsers' => $dashboard->totalUsers(),
            'totalTasks' => $dashboard->totalTasks(),
            'totalVisitors' => $dashboard->totalVisitors(),
        ];

        if ($role === 'admin') {
            require __DIR__ . '/../views/dashboard/index.php';

        } elseif ($role === 'hod') {
            require __DIR__ . '/../views/dashboard/head.php';

        } elseif ($role === 'receptionist') {
            require __DIR__ . '/../views/dashboard/receptionist.php';

        } else {
            require __DIR__ . '/../views/dashboard/staff.php';
        }
    }
}
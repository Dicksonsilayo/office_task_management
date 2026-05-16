<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| CORE DEPENDENCIES (LOAD ONCE ONLY)
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/Guard.php';

class Router
{
    public function route()
    {
        $page = $_GET['page'] ?? 'login';

        switch ($page) {

            /*
            |--------------------------------------------------------------------------
            | AUTH
            |--------------------------------------------------------------------------
            */
            case 'login':
                require_once __DIR__ . '/../controllers/AuthController.php';
                (new AuthController())->login();
                break;

            case 'logout':
                require_once __DIR__ . '/../controllers/AuthController.php';
                (new AuthController())->logout();
                break;

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */
            case 'dashboard':
                Guard::auth();
                require_once __DIR__ . '/../controllers/DashboardController.php';
                (new DashboardController())->index();
                break;

            /*
            |--------------------------------------------------------------------------
            | USERS (ADMIN ONLY)
            |--------------------------------------------------------------------------
            */
            case 'users':
                Guard::adminOnly();
                require_once __DIR__ . '/../controllers/UserController.php';
                (new UserController())->index();
                break;

            case 'create_user':
                Guard::adminOnly();
                require_once __DIR__ . '/../controllers/UserController.php';
                (new UserController())->create();
                break;

            case 'store_user':
                Guard::adminOnly();
                require_once __DIR__ . '/../controllers/UserController.php';
                (new UserController())->store();
                break;

            case 'edit_user':
                Guard::adminOnly();
                require_once __DIR__ . '/../controllers/UserController.php';
                (new UserController())->edit();
                break;

            case 'update_user':
                Guard::adminOnly();
                require_once __DIR__ . '/../controllers/UserController.php';
                (new UserController())->update();
                break;

            case 'delete_user':
                Guard::adminOnly();
                require_once __DIR__ . '/../controllers/UserController.php';
                (new UserController())->delete();
                break;

            /*
            |--------------------------------------------------------------------------
            | VISITORS (RECEPTIONIST ONLY)
            |--------------------------------------------------------------------------
            */
            case 'visitors':
                Guard::receptionistOnly();
                require_once __DIR__ . '/../controllers/VisitorController.php';
                (new VisitorController())->index();
                break;

            case 'create_visitor':
                Guard::receptionistOnly();
                require_once __DIR__ . '/../controllers/VisitorController.php';
                (new VisitorController())->create();
                break;

            case 'store_visitor':
                Guard::receptionistOnly();
                require_once __DIR__ . '/../controllers/VisitorController.php';
                (new VisitorController())->store();
                break;

            case 'checkin_visitor':
                Guard::receptionistOnly();
                require_once __DIR__ . '/../controllers/VisitorController.php';
                (new VisitorController())->checkIn();
                break;

            case 'checkout_visitor':
                Guard::receptionistOnly();
                require_once __DIR__ . '/../controllers/VisitorController.php';
                (new VisitorController())->checkOut();
                break;

            case 'visitor_history':
                Guard::receptionistOnly();
                require_once __DIR__ . '/../controllers/VisitorController.php';
                (new VisitorController())->history();
                break;

            /*
            |--------------------------------------------------------------------------
            | TASKS (ALL LOGGED USERS)
            |--------------------------------------------------------------------------
            */
            case 'tasks':
                Guard::auth();
                require_once __DIR__ . '/../controllers/TaskController.php';
                (new TaskController())->index();
                break;

            case 'task_show':
                Guard::auth();
                require_once __DIR__ . '/../controllers/TaskController.php';
                (new TaskController())->show();
                break;

            /*
            |--------------------------------------------------------------------------
            | TASK MANAGEMENT (ADMIN + HOD)
            |--------------------------------------------------------------------------
            */
            case 'create_task':
                Guard::adminOrHod();
                require_once __DIR__ . '/../controllers/TaskController.php';
                (new TaskController())->create();
                break;

            case 'store_task':
                Guard::adminOrHod();
                require_once __DIR__ . '/../controllers/TaskController.php';
                (new TaskController())->store();
                break;

            case 'update_task_status':
                Guard::auth();
                require_once __DIR__ . '/../controllers/TaskController.php';
                (new TaskController())->updateStatus();
                break;

            case 'add_comment':
                Guard::auth();
                require_once __DIR__ . '/../controllers/TaskController.php';
                (new TaskController())->addComment();
                break;

            /*
            |--------------------------------------------------------------------------
            | GOALS
            |--------------------------------------------------------------------------
            */
            case 'goals':
                Guard::adminOrHod();
                require_once __DIR__ . '/../controllers/GoalController.php';
                (new GoalController())->index();
                break;

            case 'create_goal':
                Guard::adminOrHod();
                require_once __DIR__ . '/../controllers/GoalController.php';
                (new GoalController())->create();
                break;

            case 'store_goal':
                Guard::adminOrHod();
                require_once __DIR__ . '/../controllers/GoalController.php';
                (new GoalController())->store();
                break;

            case 'delete_goal':
                Guard::adminOrHod();
                require_once __DIR__ . '/../controllers/GoalController.php';
                (new GoalController())->delete();
                break;

            /*
            |--------------------------------------------------------------------------
            | NOTIFICATIONS
            |--------------------------------------------------------------------------
            */
            case 'notifications':
                Guard::auth();
                require_once __DIR__ . '/../controllers/NotificationController.php';
                (new NotificationController())->index();
                break;

            case 'mark_notification_read':
                Guard::auth();
                require_once __DIR__ . '/../controllers/NotificationController.php';
                (new NotificationController())->read();
                break;

            case 'api_notifications':
                require_once __DIR__ . '/../controllers/api/NotificationApi.php';
                break;

            /*
            |--------------------------------------------------------------------------
            | DEFAULT
            |--------------------------------------------------------------------------
            */
            default:
                http_response_code(404);
                echo "<h1>404 Page Not Found</h1>";
                break;
        }
    }
}
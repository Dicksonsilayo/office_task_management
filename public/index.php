<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../app/configs/database.php';
require_once __DIR__ . '/../app/core/Session.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Guard.php';
require_once __DIR__ . '/../app/core/Bootstrap.php';
require_once __DIR__ . '/../app/core/Router.php';


$router = new Router();
$router->route();
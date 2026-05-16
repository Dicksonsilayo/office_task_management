<?php

// =========================
// ERROR DISPLAY (DEV ONLY)
// =========================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =========================
// START SESSION
// =========================
session_start();

// =========================
// LOAD CORE
// =========================
require_once __DIR__ . '/../../app/core/Router.php';
require_once __DIR__ . '/../../app/core/Auth.php';

// =========================
// RUN ROUTER
// =========================
$router = new Router();
$router->route();
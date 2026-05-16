<?php
require_once __DIR__ . '/../../core/Auth.php';

$authUser = Auth::user(); // SINGLE SOURCE OF TRUTH
$role = strtolower($authUser['role'] ?? 'guest');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OVTMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="container">

    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
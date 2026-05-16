<?php
// require_once __DIR__ . '/../../core/Auth.php';

 $authUser = Auth::user();
// $role = strtolower($authUser['role'] ?? 'staff');
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

    <!-- SIDEBAR -->
    <?php 
    require_once __DIR__ . '/sidebar.php'; 
    ?>

    <!-- MAIN CONTENT -->
    <div class="main-content">
<?php
require_once __DIR__ . '/../../core/Auth.php';

$user = Auth::user();

if (!$user) return;

$role = strtolower($user['role'] ?? 'staff');

$active = $_GET['page'] ?? 'dashboard';
?>

<div class="sidebar">

    <h2>OVTMS</h2>

    <a href="index.php?page=dashboard">Dashboard</a>

    <?php if ($role === 'admin'): ?>

        <a href="index.php?page=users">Manage Users</a>
        <a href="index.php?page=goals">Goals</a>
        <a href="index.php?page=tasks">Tasks</a>
        <a href="index.php?page=visitors">Visitors</a>
        <a href="index.php?page=reports">Reports</a>

    <?php elseif ($role === 'hod'): ?>

        <a href="index.php?page=goals">Goals</a>
        <a href="index.php?page=tasks">Tasks</a>
        <a href="index.php?page=reports">Reports</a>

    <?php elseif ($role === 'receptionist'): ?>

        <a href="index.php?page=visitors">Visitors</a>
        <a href="index.php?page=visitor_attendance">Attendance</a>

    <?php else: ?>

        <a href="index.php?page=tasks">My Tasks</a>

    <?php endif; ?>

    <a href="index.php?page=notifications">Notifications</a>
    <a href="index.php?page=profile">Profile</a>
    <a href="index.php?page=logout">Logout</a>

</div>
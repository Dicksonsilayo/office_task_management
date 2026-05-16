<?php

require_once __DIR__ . '/../configs/database.php';
require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/Guard.php';

// models (central loading)
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/Goal.php';
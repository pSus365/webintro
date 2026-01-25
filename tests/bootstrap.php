<?php

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/controllers/AppController.php';

// Mock session if needed or just start it for tests that require it
if (session_status() == PHP_SESSION_NONE) {
    // session_start(); 
    // Usually avoid starting actual session in CLI, but for simple integration tests it might be needed.
    // For now we will rely on basic inclusion.
}

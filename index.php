<?php

require_once 'src/config.php';
require_once 'Routing.php';


$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

// Basic error handling for production
error_reporting(E_ALL);
ini_set('display_errors', 0);

set_exception_handler(function ($e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo "<h1>500 Internal Server Error</h1>";
    echo "<p>Something went wrong. Please try again later.</p>";
    exit();
});

Routing::run($path);
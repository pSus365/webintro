<?php

require_once 'src/config.php';
require_once 'Routing.php';


// Debugging 404
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

// echo "Debug Path: [" . $path . "] <br>";
// echo "Request URI: [" . $_SERVER['REQUEST_URI'] . "] <br>";

try {
    Routing::run($path);
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    include 'public/views/500.html';
}
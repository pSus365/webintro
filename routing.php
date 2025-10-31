<?php
require_once 'src/controllers/SecurityController.php';

class Routing {
    public static $routes = [
        "login"     => ["controller" => "SecurityController",  "action" => "login"],
        "dashboard" => ["controller" => "DashboardController", "action" => "dashboard"],
        "pojazdy"   => ["controller" => "DashboardController", "action" => "vehicles"],   // na razie widok statyczny
        "kierowcy"  => ["controller" => "DashboardController", "action" => "drivers"],
        "zlecenia"  => ["controller" => "DashboardController", "action" => "orders"],
        "ustawienia"=> ["controller" => "DashboardController", "action" => "settings"],
    ];

    public static function run(string $path) {
        switch ($path) {
            case 'dashboard':
                include 'public/views/dashboard.html';
                break;

            case 'pojazdy':
                include 'public/views/pojazdy.html';
                break;

            case 'kierowcy':
                include 'public/views/kierowcy.html';
                break;

            case 'zlecenia':
                include 'public/views/zlecenia.html';
                break;

            case 'ustawienia':
                include 'public/views/ustawienia.html';
                break;

            case 'login':
            case 'register':
                $controller = Routing::$routes[$path]["controller"];
                $action     = Routing::$routes[$path]["action"];
                $controllerObj = new $controller;
                $controllerObj->$action();
                break;

            default:
                http_response_code(404);
                include 'public/views/404.html';
                break;
        }
    }
}

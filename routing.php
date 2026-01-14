<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';

class Routing
{
    public static $routes = [
        "login" => [
            "controller" => "SecurityController",
            "action" => "login"
        ],
        "dashboard" => [
            "controller" => "DashboardController",
            "action" => "dashboard"
        ]
    ];

    public static function run(string $path)
    {
        switch ($path) {
            case 'dashboard':
                $controller = new DashboardController();
                $controller->dashboard();
                break;

            case 'login':
            case 'register':
                $controller = Routing::$routes[$path]["controller"];
                $action = Routing::$routes[$path]["action"];

                $object = new $controller;
                $object->$action();
                break;


                break;

            default:
                echo "<h1>H1 404</h1>";
                include 'public/views/404.html';
                break;
        }
    }
}

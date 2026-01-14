<?php

require_once 'src/controllers/SecurityController.php';

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
                // TODO: conect with databsse
                // get elements to present on dashboard
                include 'public/views/dashboard.html';
                break;

            case 'login':
            case 'register':
                // TODO: get from form user email and password
                // check in database if user exists
                //if user exists, redirect to dashboard
                //if user does not exist, show error message
                $controller = Routing::$routes[$path]["controller"];
                $action = Routing::$routes[$path]["action"];

                $controllerObj = new $controller;
                $controllerObj->$action();


                break;

            default:
                echo "<h1>H1 404</h1>";
                include 'public/views/404.html';
                break;
        }
    }
}

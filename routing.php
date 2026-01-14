<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/VehicleController.php';
require_once 'src/controllers/DriverController.php';
require_once 'src/controllers/MaintenanceController.php';
require_once 'src/controllers/ReportsController.php';
require_once 'src/controllers/RemindersController.php';
require_once 'src/controllers/UserController.php';

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
        ],
        "vehicles" => [
            "controller" => "VehicleController",
            "action" => "index"
        ],
        "add-vehicle" => [
            "controller" => "VehicleController",
            "action" => "addVehicle"
        ],
        "edit-vehicle" => [
            "controller" => "VehicleController",
            "action" => "editVehicle"
        ],
        "delete-vehicle" => [
            "controller" => "VehicleController",
            "action" => "deleteVehicle"
        ],
        "drivers" => [
            "controller" => "DriverController",
            "action" => "index"
        ],
        "add-driver" => [
            "controller" => "DriverController",
            "action" => "addDriver"
        ],
        "edit-driver" => [
            "controller" => "DriverController",
            "action" => "editDriver"
        ],
        "delete-driver" => [
            "controller" => "DriverController",
            "action" => "deleteDriver"
        ],
        "maintenance" => [
            "controller" => "MaintenanceController",
            "action" => "index"
        ],
        "maintenance/stats" => [
            "controller" => "MaintenanceController",
            "action" => "stats"
        ],
        "raports" => [
            "controller" => "ReportsController",
            "action" => "index"
        ],
        "reminders" => [
            "controller" => "RemindersController",
            "action" => "index"
        ],
        "user" => [
            "controller" => "UserController",
            "action" => "profile"
        ],
        "user/update" => [
            "controller" => "UserController",
            "action" => "update"
        ],
        "user/upload-avatar" => [
            "controller" => "UserController",
            "action" => "uploadAvatar"
        ]
    ];

    public static function run(string $path)
    {
        switch ($path) {
            case 'dashboard':
                $controller = new DashboardController();
                $controller->dashboard();
                break;

            case 'vehicles':
                $controller = new VehicleController();
                $controller->index();
                break;

            case 'add-vehicle':
                $controller = new VehicleController();
                $controller->addVehicle();
                break;

            case 'edit-vehicle':
                $controller = new VehicleController();
                $controller->editVehicle();
                break;

            case 'delete-vehicle':
                $controller = new VehicleController();
                $controller->deleteVehicle();
                break;

            case 'drivers':
                $controller = new DriverController();
                $controller->index();
                break;

            case 'add-driver':
                $controller = new DriverController();
                $controller->addDriver();
                break;

            case 'edit-driver':
                $controller = new DriverController();
                $controller->editDriver();
                break;

            case 'delete-driver':
                $controller = new DriverController();
                $controller->deleteDriver();
                break;

            case 'maintenance':
                $controller = new MaintenanceController();
                $controller->index();
                break;

            case 'maintenance/stats':
                $controller = new MaintenanceController();
                $controller->stats();
                break;

            case 'raports':
                $controller = new ReportsController();
                $controller->index();
                break;

            case 'reminders':
                $controller = new RemindersController();
                $controller->index();
                break;

            case 'user':
                $controller = new UserController();
                $controller->profile();
                break;

            case 'user/update':
                $controller = new UserController();
                $controller->update();
                break;

            case 'user/upload-avatar':
                $controller = new UserController();
                $controller->uploadAvatar();
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

<?php

class Routing {
    public static function run(string $path) {
        switch ($path) {
            case 'dashboard':
           echo "<h1>H1 Dashboard</h1>";
           include 'public/views/dashboard.html';
           break;
       case 'login':
           echo "<h1>H1 Login</h1>";
           include 'public/views/login.html';
           break;
       default:
           echo "<h1>H1 404</h1>";
           include 'public/views/404.html';
           break;
       }
    }
}
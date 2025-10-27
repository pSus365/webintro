<?php

require_once 'AppController.php';

class SecurityController extends AppController {
    public function login() {
        // TODO: zwroc html, przerworz dane
        
        
        
        // przekazywanie tablicy asocjacyjnej do widoku
       return $this->render('login', ['message' => 'Bledne haslo lub email']);


    }
}
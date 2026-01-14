<?php

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../config.php';

class Repository
{
    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }
}

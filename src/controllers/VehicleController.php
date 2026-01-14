<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/VehicleRepository.php';

class VehicleController extends AppController
{

    private $vehicleRepository;

    public function __construct()
    {
        $this->vehicleRepository = new VehicleRepository();
    }

    public function index()
    {
        $vehicles = $this->vehicleRepository->getVehicles();

        $this->render('vehicles', [
            'vehicles' => $vehicles
        ]);
    }
}

<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/VehicleRepository.php';
require_once __DIR__ . '/../repository/DriverRepository.php';

class ReportsController extends AppController
{
    private $vehicleRepository;
    private $driverRepository;

    public function __construct()
    {
        $this->vehicleRepository = new VehicleRepository();
        $this->driverRepository = new DriverRepository();
    }

    public function index()
    {
        $vehicles = $this->vehicleRepository->getVehicles();
        $drivers = $this->driverRepository->getDrivers();

        // Pass data to view
        $this->render('raports', [
            'vehicles' => $vehicles,
            'drivers' => $drivers
        ]);
    }
}

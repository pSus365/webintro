<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/VehicleRepository.php';

class DashboardController extends AppController
{

    private $vehicleRepository;

    public function __construct()
    {
        $this->vehicleRepository = new VehicleRepository();
    }

    public function dashboard()
    {
        $vehicles = $this->vehicleRepository->getVehicles();
        $stats = $this->vehicleRepository->getStatistics();
        $upcomingMaintenances = $this->vehicleRepository->getUpcomingMaintenances();

        $this->render('dashboard', [
            'vehicles' => $vehicles,
            'stats' => $stats,
            'upcomingMaintenances' => $upcomingMaintenances
        ]);
    }
}

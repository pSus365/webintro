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

        // Mock data for fuel prices
        $fuelPrices = [
            'Pb95' => 6.45,
            'Pb98' => 6.98,
            'ON' => 6.59,
            'LPG' => 2.85
        ];

        $this->render('dashboard', [
            'vehicles' => $vehicles,
            'stats' => $stats,
            'upcomingMaintenances' => $upcomingMaintenances,
            'fuelPrices' => $fuelPrices
        ]);
    }
}

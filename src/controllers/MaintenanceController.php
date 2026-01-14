<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/VehicleRepository.php';

class MaintenanceController extends AppController
{
    private $vehicleRepository;

    public function __construct()
    {
        $this->vehicleRepository = new VehicleRepository();
    }

    public function index()
    {
        $vehicles = $this->vehicleRepository->getVehicles();
        $this->render('maintenance', ['vehicles' => $vehicles]);
    }

    public function stats()
    {
        $vehicles = $this->vehicleRepository->getVehicles();

        $totalCost = 0;
        $typeCosts = [];
        $upcomingServiceCount = 0;

        foreach ($vehicles as $vehicle) {
            $cost = floatval($vehicle['estimated_service_cost']);
            $totalCost += $cost;

            $type = $vehicle['type'];
            if (!isset($typeCosts[$type])) {
                $typeCosts[$type] = 0;
            }
            $typeCosts[$type] += $cost;

            // Simple logic for upcoming service (e.g. next 30 days) - for now just count all with date set
            if ($vehicle['next_service_date']) {
                $upcomingServiceCount++;
            }
        }

        $this->render('maintenance_stats', [
            'totalCost' => $totalCost,
            'typeCosts' => $typeCosts,
            'vehicleCount' => count($vehicles),
            'upcomingServiceCount' => $upcomingServiceCount
        ]);
    }
}

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
        $maintenances = $this->vehicleRepository->getAllMaintenances();

        $this->render('maintenance', [
            'vehicles' => $vehicles,
            'maintenances' => $maintenances
        ]);
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

    public function addMaintenance()
    {
        if ($this->isPost()) {
            $vehicle_id = $_POST['vehicle_id'];
            $description = $_POST['description'];
            $cost = $_POST['cost'];
            $maintenance_date = $_POST['maintenance_date'];

            // Default status to pending if not provided
            $status = 'pending';

            $maintenance = [
                'vehicle_id' => $vehicle_id,
                'description' => $description,
                'cost' => $cost,
                'maintenance_date' => $maintenance_date,
                'status' => $status
            ];

            $this->vehicleRepository->addMaintenance($maintenance);

            // Handle "Is Inspection" Checkbox
            if (isset($_POST['is_inspection'])) {
                // Determine next service date (e.g., 1 year from now or just use the provided date as the start of the new period?)
                // Usually "Upcoming Service" means "set the date for the NEXT one". 
                // Creating a maintenance record usually means creating a HISTORY record or a PLANNED one.
                // If user says "Add Upcoming Service" via this form, they likely want to set the vehicle's next_service_date property to THIS date.

                $this->vehicleRepository->updateVehicleServiceInfo($vehicle_id, $maintenance_date, $cost);
            }

            header("Location: /maintenance");
            exit();
        }

        // GET request - show form
        // If vehicle_id is passed, we can pre-select it
        $selectedVehicleId = $_GET['vehicle_id'] ?? null;
        $vehicles = $this->vehicleRepository->getVehicles();

        $this->render('maintenance_form', [
            'vehicles' => $vehicles,
            'selectedVehicleId' => $selectedVehicleId
        ]);
    }
}

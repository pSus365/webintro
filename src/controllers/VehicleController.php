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
        $filter = $_GET['filter'] ?? 'all';

        if ($filter === 'active') {
            $vehicles = array_filter($vehicles, function ($vehicle) {
                return $vehicle['status'] === 'wolny';
            });
        } elseif ($filter === 'inactive') {
            $vehicles = array_filter($vehicles, function ($vehicle) {
                return $vehicle['status'] !== 'wolny';
            });
        }

        $this->render('vehicles', [
            'vehicles' => $vehicles,
            'currentFilter' => $filter
        ]);
    }
}

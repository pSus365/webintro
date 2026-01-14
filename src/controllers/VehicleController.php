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

        $search = $_GET['search'] ?? null;
        if ($search) {
            $vehicles = array_filter($vehicles, function ($vehicle) use ($search) {
                return stripos($vehicle['name'], $search) !== false;
            });
        }

        $this->render('vehicles', [
            'vehicles' => $vehicles,
            'currentFilter' => $filter,
            'search' => $search
        ]);
    }

    public function addVehicle()
    {
        if ($this->isPost()) {
            $name = $_POST['name'];
            $type = $_POST['type'];
            $mileage = $_POST['mileage'];
            $next_service_date = $_POST['next_service_date'];
            $estimated_service_cost = $_POST['estimated_service_cost'];
            $status = $_POST['status'];

            $vehicle = [
                'name' => $name,
                'type' => $type,
                'mileage' => $mileage,
                'next_service_date' => $next_service_date,
                'estimated_service_cost' => $estimated_service_cost,
                'status' => $status
            ];

            $this->vehicleRepository->addVehicle($vehicle);
            return $this->render('vehicles', ['vehicles' => $this->vehicleRepository->getVehicles()]);
        }

        $this->render('vehicle_form', ['action' => 'add']);
    }

    public function editVehicle()
    {
        if ($this->isPost()) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $type = $_POST['type'];
            $mileage = $_POST['mileage'];
            $next_service_date = $_POST['next_service_date'];
            $estimated_service_cost = $_POST['estimated_service_cost'];
            $status = $_POST['status'];

            $vehicle = [
                'name' => $name,
                'type' => $type,
                'mileage' => $mileage,
                'next_service_date' => $next_service_date,
                'estimated_service_cost' => $estimated_service_cost,
                'status' => $status
            ];

            $this->vehicleRepository->updateVehicle($id, $vehicle);
            return $this->render('vehicles', ['vehicles' => $this->vehicleRepository->getVehicles()]);
        }

        $id = $_GET['id'];
        $vehicle = $this->vehicleRepository->getVehicle($id);
        $this->render('vehicle_form', ['vehicle' => $vehicle, 'action' => 'edit']);
    }

    public function deleteVehicle()
    {
        if ($this->isPost()) {
            $id = $_POST['id'];
            $this->vehicleRepository->deleteVehicle($id);
        }
        return $this->render('vehicles', ['vehicles' => $this->vehicleRepository->getVehicles()]);
    }
}

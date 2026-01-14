<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/VehicleRepository.php';

class RemindersController extends AppController
{
    private $vehicleRepository;

    public function __construct()
    {
        $this->vehicleRepository = new VehicleRepository();
    }

    public function index()
    {
        $vehicles = $this->vehicleRepository->getVehicles();

        // Filter out vehicles without service date and sort by date ASC
        $upcomingServices = array_filter($vehicles, function ($v) {
            return !empty($v['next_service_date']);
        });

        usort($upcomingServices, function ($a, $b) {
            return strtotime($a['next_service_date']) - strtotime($b['next_service_date']);
        });

        $this->render('reminders', ['reminders' => $upcomingServices]);
    }
}

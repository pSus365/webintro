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
        // Fetch up to 50 upcoming maintenance records
        $upcomingServices = $this->vehicleRepository->getUpcomingMaintenances(50);

        // Filter out past dates if strictly "upcoming" desired, but query asks for all. 
        // Let's filter in PHP if needed, or rely on SQL. 
        // The current query orders by date ASC. It might include past unpaid ones? 
        // Let's assume the user wants to see all scheduled things.

        // Actually, let's just pass them. The view handles "overdue" logic.

        $this->render('reminders', ['reminders' => $upcomingServices]);
    }
}

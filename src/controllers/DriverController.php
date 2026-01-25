<?php

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/DriverRepository.php';

class DriverController extends AppController
{

    private $driverRepository;

    public function __construct()
    {
        $this->driverRepository = new DriverRepository();
    }

    public function index()
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            include __DIR__ . '/../../public/views/403.html';
            return;
        }

        $drivers = $this->driverRepository->getDrivers();
        $filter = $_GET['filter'] ?? 'all';

        if ($filter === 'active') {
            $drivers = array_filter($drivers, function ($driver) {
                return $driver['status'] === 'wolny';
            });
        } elseif ($filter === 'inactive') {
            $drivers = array_filter($drivers, function ($driver) {
                return $driver['status'] !== 'wolny';
            });
        }

        $search = $_GET['search'] ?? null;
        if ($search) {
            $drivers = array_filter($drivers, function ($driver) use ($search) {
                return stripos($driver['first_name'], $search) !== false || stripos($driver['last_name'], $search) !== false;
            });
        }

        $this->render('drivers', [
            'drivers' => $drivers,
            'currentFilter' => $filter,
            'search' => $search
        ]);
    }

    public function addDriver()
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            include __DIR__ . '/../../public/views/403.html';
            return;
        }

        if ($this->isPost()) {
            $driver = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'birth_date' => $_POST['birth_date'],
                'city' => $_POST['city'],
                'street' => $_POST['street'],
                'house_number' => $_POST['house_number'],
                'employment_date' => $_POST['employment_date'],
                'status' => $_POST['status']
            ];

            $this->driverRepository->addDriver($driver);
            return $this->render('drivers', ['drivers' => $this->driverRepository->getDrivers()]);
        }

        $this->render('driver_form', ['action' => 'add']);
    }

    public function editDriver()
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            include __DIR__ . '/../../public/views/403.html';
            return;
        }

        if ($this->isPost()) {
            $id = $_POST['id'];
            $driver = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'birth_date' => $_POST['birth_date'],
                'city' => $_POST['city'],
                'street' => $_POST['street'],
                'house_number' => $_POST['house_number'],
                'employment_date' => $_POST['employment_date'],
                'status' => $_POST['status']
            ];

            $this->driverRepository->updateDriver($id, $driver);
            return $this->render('drivers', ['drivers' => $this->driverRepository->getDrivers()]);
        }

        $id = $_GET['id'];
        $driver = $this->driverRepository->getDriver($id);
        $this->render('driver_form', ['driver' => $driver, 'action' => 'edit']);
    }

    public function deleteDriver()
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            include __DIR__ . '/../../public/views/403.html';
            return;
        }

        if ($this->isPost()) {
            $id = $_POST['id'];
            $this->driverRepository->deleteDriver($id);
        }
        return $this->render('drivers', ['drivers' => $this->driverRepository->getDrivers()]);
    }
}

<?php

require_once __DIR__ . '/Repository.php';

class VehicleRepository extends Repository
{

    public function getVehicles(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM vehicles
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatistics(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT status, COUNT(*) as count FROM vehicles GROUP BY status
        ');
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stats = [
            'wolny' => 0,
            'w trasie' => 0,
            'serwis' => 0
        ];

        foreach ($results as $row) {
            if (array_key_exists($row['status'], $stats)) {
                $stats[$row['status']] = $row['count'];
            }
        }

        return $stats;
    }

    public function getUpcomingMaintenances(int $limit = 5): array
    {
        $stmt = $this->database->connect()->prepare("
            SELECT m.*, v.name as vehicle_name 
            FROM maintenances m
            JOIN vehicles v ON m.vehicle_id = v.id
            ORDER BY m.maintenance_date ASC
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMaintenance(array $maintenance): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO maintenances (vehicle_id, description, cost, maintenance_date, status)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $maintenance['vehicle_id'],
            $maintenance['description'],
            $maintenance['cost'],
            $maintenance['maintenance_date'],
            $maintenance['status']
        ]);
    }

    public function getAllMaintenances(): array
    {
        // For the full maintenance history table
        $stmt = $this->database->connect()->prepare('
            SELECT m.*, v.name as vehicle_name, v.type as vehicle_type 
            FROM maintenances m
            JOIN vehicles v ON m.vehicle_id = v.id
            ORDER BY m.maintenance_date DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateVehicleServiceInfo(int $vehicleId, string $nextServiceDate, float $estimatedCost)
    {
        $stmt = $this->database->connect()->prepare('
            UPDATE vehicles 
            SET next_service_date = ?, estimated_service_cost = ?, status = ?
            WHERE id = ?
        ');
        // Setting status to 'wolny' assuming maintenance is future planning, OR keep existing status?
        // Let's keep existing status by reading it first OR just updating specific fields.
        // Actually, requirement says "Update reminders". Reminders use next_service_date.
        // Let's safe update only service fields.

        $stmt = $this->database->connect()->prepare('
            UPDATE vehicles 
            SET next_service_date = ?, estimated_service_cost = ?
            WHERE id = ?
        ');
        $stmt->execute([
            $nextServiceDate,
            $estimatedCost,
            $vehicleId
        ]);
    }

    public function getVehicle(int $id)
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM vehicles WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vehicle == false) {
            return null;
        }

        return $vehicle;
    }

    public function addVehicle(array $vehicle): void
    {
        // $vehicle array structure: name, type, mileage, next_service_date, estimated_service_cost, status
        $stmt = $this->database->connect()->prepare('
            INSERT INTO vehicles (name, type, mileage, next_service_date, estimated_service_cost, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $vehicle['name'],
            $vehicle['type'],
            $vehicle['mileage'],
            $vehicle['next_service_date'],
            $vehicle['estimated_service_cost'],
            $vehicle['status']
        ]);
    }

    public function updateVehicle(int $id, array $vehicle): void
    {
        $stmt = $this->database->connect()->prepare('
            UPDATE vehicles 
            SET name = ?, type = ?, mileage = ?, next_service_date = ?, estimated_service_cost = ?, status = ?
            WHERE id = ?
        ');

        $stmt->execute([
            $vehicle['name'],
            $vehicle['type'],
            $vehicle['mileage'],
            $vehicle['next_service_date'],
            $vehicle['estimated_service_cost'],
            $vehicle['status'],
            $id
        ]);
    }

    public function deleteVehicle(int $id): void
    {
        $stmt = $this->database->connect()->prepare('
            DELETE FROM vehicles WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

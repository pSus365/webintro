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

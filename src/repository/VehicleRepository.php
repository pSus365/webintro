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
}

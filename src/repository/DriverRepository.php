<?php

require_once __DIR__ . '/Repository.php';

class DriverRepository extends Repository
{

    public function getDrivers(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM drivers
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDriver(int $id)
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM drivers WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $driver = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($driver == false) {
            return null;
        }

        return $driver;
    }

    public function addDriver(array $driver): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO drivers (first_name, last_name, birth_date, city, street, house_number, employment_date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $driver['first_name'],
            $driver['last_name'],
            $driver['birth_date'],
            $driver['city'],
            $driver['street'],
            $driver['house_number'],
            $driver['employment_date'],
            $driver['status']
        ]);
    }

    public function updateDriver(int $id, array $driver): void
    {
        $stmt = $this->database->connect()->prepare('
            UPDATE drivers 
            SET first_name = ?, last_name = ?, birth_date = ?, city = ?, street = ?, house_number = ?, employment_date = ?, status = ?
            WHERE id = ?
        ');

        $stmt->execute([
            $driver['first_name'],
            $driver['last_name'],
            $driver['birth_date'],
            $driver['city'],
            $driver['street'],
            $driver['house_number'],
            $driver['employment_date'],
            $driver['status'],
            $id
        ]);
    }

    public function deleteDriver(int $id): void
    {
        $stmt = $this->database->connect()->prepare('
            DELETE FROM drivers WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

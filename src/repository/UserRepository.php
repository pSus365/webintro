<?php

require_once __DIR__ . '/../Database.php';

class UserRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUser(int $id)
    {
        $stmt = $this->db->connect()->prepare('
            SELECT * FROM users WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser(int $id, string $name, string $surname)
    {
        $stmt = $this->db->connect()->prepare('
            UPDATE users SET name = :name, surname = :surname WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function updateAvatar(int $id, string $avatarUrl)
    {
        $stmt = $this->db->connect()->prepare('
            UPDATE users SET avatar_url = :avatar_url WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':avatar_url', $avatarUrl, PDO::PARAM_STR);

        return $stmt->execute();
    }
}

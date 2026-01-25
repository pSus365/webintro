<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/repository/UserRepository.php';

class UserRepositoryTest extends TestCase
{
    public function testGetInstance()
    {
        $repo1 = UserRepository::getInstance();
        $repo2 = UserRepository::getInstance();

        $this->assertInstanceOf(UserRepository::class, $repo1);
        $this->assertSame($repo1, $repo2);
    }

    // Note: Testing actual DB methods would require a test database setup 
    // or mocking PDO which is complex with the current Singleton/Direct DB new instantiation.
    // For this scope, we verify structural integrity and Singleton pattern.
}

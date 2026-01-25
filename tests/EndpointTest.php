<?php

use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    private $baseUrl = 'http://webintro-web-1:80'; // Requesting container directly if running from inside network, or localhost if from outside

    // However, PHPUnit likely runs locally or in a separate container. 
    // If running from host logic, we hit localhost:8080

    protected function setUp(): void
    {
        // When running inside docker container 'php', 'web' is the hostname of nginx
        $this->baseUrl = getenv('TEST_BASE_URL') ?: 'http://web';
    }

    public function testLoginPageIsAccessible()
    {
        $ch = curl_init($this->baseUrl . '/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(200, $httpCode);
        $this->assertStringContainsString('Zaloguj się', $response);
    }

    public function testRegisterPageIsAccessible()
    {
        $ch = curl_init($this->baseUrl . '/register');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(200, $httpCode);
        $this->assertStringContainsString('Rejestracja', $response);
    }

    public function testNonExistentPageReturns404()
    {
        $ch = curl_init($this->baseUrl . '/non-existent-page-12345');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(404, $httpCode);
    }
}

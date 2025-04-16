<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HealthCheckControllerTest extends WebTestCase
{
    public function testHealthCheck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/healthcheck');

        $content = json_decode($client->getResponse()->getContent(), associative: true);

        $this->assertResponseIsSuccessful();
        $this->assertTrue($content['success']);
    }
}

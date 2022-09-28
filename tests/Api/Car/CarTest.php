<?php

namespace App\Tests\Api\Car;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CarTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $response = static::createClient()->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/']);
    }
}

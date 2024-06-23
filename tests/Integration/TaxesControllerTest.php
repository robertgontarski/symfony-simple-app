<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaxesControllerTest extends WebTestCase
{
    public function testGetTaxesWithoutCountry(): void
    {
        $client = static::createClient();
        $client->request('GET', '/taxes');

        $this->assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('data', $response);

        $this->assertEquals(400, $response['status']);
        $this->assertEquals('validation error', $response['message']);
    }

    // test request with not supported country
    public function testGetTaxesWithNotSupportedCountry(): void
    {
        $client = static::createClient();
        $client->request('GET', '/taxes?country=XX');

        $this->assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('data', $response);

        $this->assertEquals(400, $response['status']);
        $this->assertEquals('validation error', $response['message']);
    }

    public function testGetTaxesWithSupportedCountryNotReqState(): void
    {
        $client = static::createClient();
        $client->request('GET', '/taxes?country=LT');

        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('data', $response);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['message']);
    }

    public function testGetTaxesWithSupportedCountryReqStateWithoutState(): void
    {
          $client = static::createClient();
            $client->request('GET', '/taxes?country=US');

            $this->assertResponseStatusCodeSame(400);

            $response = json_decode($client->getResponse()->getContent(), true);

            $this->assertArrayHasKey('status', $response);
            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('data', $response);

            $this->assertEquals(400, $response['status']);
            $this->assertEquals('validation error', $response['message']);
    }

    public function testGetTaxesWithSupportedCountryReqStateWithState(): void
    {
        $client = static::createClient();
        $client->request('GET', '/taxes?country=US&state=Ontario');

        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('data', $response);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals('success', $response['message']);

        $this->assertArrayHasKey('taxes', $response['data']);

        $this->assertIsArray($response['data']['taxes']);
        $this->assertCount(1, $response['data']['taxes']);

        $this->assertArrayHasKey('taxType', $response['data']['taxes'][0]);
        $this->assertArrayHasKey('percentage', $response['data']['taxes'][0]);

        $this->assertEquals('VAT', $response['data']['taxes'][0]['taxType']);
        $this->assertEquals(20, $response['data']['taxes'][0]['percentage']);
    }

    public function testGetTaxesWithTimeout(): void
    {
        $client = static::createClient();
        $client->request('GET', '/taxes?country=PL');

        $this->assertResponseStatusCodeSame(504);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('data', $response);

        $this->assertEquals(504, $response['status']);
        $this->assertEquals('timeout while try to get taxes', $response['message']);
    }
}

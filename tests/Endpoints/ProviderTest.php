<?php

namespace Endpoints;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use SpinupWp\Endpoints\Provider;
use SpinupWp\SpinupWp;

class ProviderTest extends TestCase
{
    public SpinupWp $spinupwp;

    public Provider $endpoint;

    public Client $client;

    public function setUp(): void
    {
        $this->client   = Mockery::mock(Client::class);
        $this->spinupwp = new SpinupWp('123', $this->client);
        $this->endpoint = new Provider($this->spinupwp);
    }

    public function test_metadata_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'providers/digitalocean/metadata', [])->andReturn(
            new Response(200, [], json_encode(['regions' => [], 'sizes' => []]))
        );

        $result = $this->endpoint->metadata('digitalocean');
        $this->assertArrayHasKey('regions', $result);
        $this->assertArrayHasKey('sizes', $result);
    }
}

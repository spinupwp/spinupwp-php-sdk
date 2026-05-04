<?php

namespace Endpoints;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use SpinupWp\Endpoints\StorageProvider;
use SpinupWp\SpinupWp;

class StorageProviderTest extends TestCase
{
    public SpinupWp $spinupwp;

    public StorageProvider $endpoint;

    public Client $client;

    public function setUp(): void
    {
        $this->client   = Mockery::mock(Client::class);
        $this->spinupwp = new SpinupWp('123', $this->client);
        $this->endpoint = new StorageProvider($this->spinupwp);
    }

    public function test_list_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'storage-providers', [])->andReturn(
            new Response(200, [], '{"data": [{"id": 1, "service": "aws-s3", "name": "Production backups"}]}')
        );

        $storageProviders = $this->endpoint->list();
        $this->assertCount(1, $storageProviders);
        $this->assertEquals('aws-s3', $storageProviders[0]['service']);
    }
}

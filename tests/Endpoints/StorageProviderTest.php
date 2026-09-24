<?php

namespace Endpoints;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use SpinupWp\Endpoints\StorageProvider;
use SpinupWp\Resources\ResourceCollection;
use SpinupWp\Resources\StorageProvider as StorageProviderResource;
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
        $this->client->shouldReceive('request')->once()->with('GET', 'storage-providers?page=1', [])->andReturn(
            new Response(200, [], '{"data": [{"id": 1, "service": "aws-s3", "name": "Production backups"}], "pagination": {"previous": null, "next": null, "count": 1}}')
        );

        $storageProviders = $this->endpoint->list();
        $this->assertInstanceOf(ResourceCollection::class, $storageProviders);
        $this->assertCount(1, $storageProviders);
        $this->assertInstanceOf(StorageProviderResource::class, $storageProviders->toArray()[0]);
        $this->assertEquals('aws-s3', $storageProviders->toArray()[0]->service);
    }

    public function test_list_request_with_pagination_parameters(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'storage-providers?page=2&limit=100', [])->andReturn(
            new Response(200, [], '{"data": [{"id": 1, "service": "aws-s3"}], "pagination": {"previous": null, "next": null, "count": 1}}')
        );

        $storageProviders = $this->endpoint->list(2, ['limit' => 100]);
        $this->assertCount(1, $storageProviders);
    }
}

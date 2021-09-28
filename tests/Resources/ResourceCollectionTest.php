<?php

use DeliciousBrains\SpinupWp\Endpoints\Server as ServerEndpoint;
use DeliciousBrains\SpinupWp\Resources\ResourceCollection;
use DeliciousBrains\SpinupWp\Resources\Server as ServerResource;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ResourceCollectionTest extends TestCase
{
    protected array $payload;

    public function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'data'       => [
                ['name' => 'hellfish-media'],
                ['name' => 'staging-hellfish-media'],
            ],
            'pagination' => [
                'previous' => null,
                'next'     => null,
                'count'    => 2,
            ],
        ];
    }

    public function test_resources_are_mapped(): void
    {
        $endpoint = new ServerEndpoint(Mockery::mock(Client::class));
        $servers  = (new ResourceCollection($this->payload, ServerResource::class, $endpoint));

        $this->assertInstanceOf(ServerResource::class, $servers->toArray()[0]);
    }

    public function test_resources_are_countable(): void
    {
        $endpoint = new ServerEndpoint(Mockery::mock(Client::class));
        $servers  = new ResourceCollection($this->payload, ServerResource::class, $endpoint);

        $this->assertEquals(2, $servers->count());
    }

    public function test_resources_are_arrayable(): void
    {
        $endpoint = new ServerEndpoint(Mockery::mock(Client::class));
        $servers  = (new ResourceCollection($this->payload, ServerResource::class, $endpoint))->toArray();

        $this->assertIsArray($servers);
        $this->assertEquals('hellfish-media', $servers[0]->name);
    }

    public function test_resources_can_be_iterated(): void
    {
        $client   = Mockery::mock(Client::class);
        $endpoint = new ServerEndpoint($client);

        $this->payload['pagination']['next'] = 'https://api.spinupwp.app/v1/servers';

        $client->shouldReceive('request')->once()->with('GET', 'servers?page=2', [])->andReturn(
            new Response(200, [], '{"data": [{"name": "dev-hellfish-media"}], "pagination": {"previous": null, "next": null, "count": 3}}')
        );

        $servers = (new ResourceCollection($this->payload, ServerResource::class, $endpoint))->toArray();

        $this->assertCount(3, $servers);
        $this->assertEquals('dev-hellfish-media', $servers[2]->name);
    }

    public function test_resources_have_payload(): void
    {
        $endpoint = new ServerEndpoint(Mockery::mock(Client::class));
        $servers  = new ResourceCollection($this->payload, ServerResource::class, $endpoint);

        $this->assertEquals($this->payload, $servers->payload());
    }
}
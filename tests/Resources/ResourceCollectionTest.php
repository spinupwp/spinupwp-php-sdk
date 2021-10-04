<?php

use DeliciousBrains\SpinupWp\Endpoints\Server as ServerEndpoint;
use DeliciousBrains\SpinupWp\Resources\ResourceCollection;
use DeliciousBrains\SpinupWp\Resources\Server as ServerResource;
use DeliciousBrains\SpinupWp\SpinupWp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ResourceCollectionTest extends TestCase
{
    protected array $payload;
    protected SpinupWp $spinupwp;
    protected Client $client;
    protected ServerEndpoint $serverEndpoint;

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
        $this->spinupwp       = Mockery::mock(SpinupWp::class);
        $this->client         = Mockery::mock(Client::class);
        $this->serverEndpoint = new ServerEndpoint(Mockery::mock(Client::class), $this->spinupwp);
    }

    public function test_resources_are_mapped(): void
    {
        $servers  = (new ResourceCollection($this->payload, ServerResource::class, $this->serverEndpoint, $this->spinupwp));

        $this->assertInstanceOf(ServerResource::class, $servers->toArray()[0]);
    }

    public function test_resources_are_countable(): void
    {
        $servers  = new ResourceCollection($this->payload, ServerResource::class, $this->serverEndpoint, $this->spinupwp);

        $this->assertEquals(2, $servers->count());
    }

    public function test_resources_are_arrayable(): void
    {
        $servers  = (new ResourceCollection($this->payload, ServerResource::class, $this->serverEndpoint, $this->spinupwp))->toArray();

        $this->assertIsArray($servers);
        $this->assertEquals('hellfish-media', $servers[0]->name);
    }

    public function test_resources_can_be_iterated(): void
    {
        $this->payload['pagination']['next'] = 'https://api.spinupwp.app/v1/servers';

        $this->client->shouldReceive('request')->once()->with('GET', 'servers?page=2', [])->andReturn(
            new Response(200, [], '{"data": [{"name": "dev-hellfish-media"}], "pagination": {"previous": null, "next": null, "count": 3}}')
        );

        $servers = (new ResourceCollection($this->payload, ServerResource::class, $this->serverEndpoint, $this->spinupwp))->toArray();

        $this->assertCount(3, $servers);
        $this->assertEquals('dev-hellfish-media', $servers[2]->name);
    }

    public function test_resources_have_payload(): void
    {
        $servers  = new ResourceCollection($this->payload, ServerResource::class, $this->serverEndpoint, $this->spinupwp);

        $this->assertEquals($this->payload, $servers->payload());
    }
}
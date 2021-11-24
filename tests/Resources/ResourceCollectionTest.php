<?php

use DeliciousBrains\SpinupWp\Endpoints\Server as ServerEndpoint;
use DeliciousBrains\SpinupWp\Resources\Paginator;
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

        $this->payload        = [
            'data'       => [
                ['name' => 'hellfish-media'],
                ['name' => 'staging-hellfish-media'],
            ],
            'pagination' => [
                'previous' => null,
                'next'     => null,
                'count'    => 3,
            ],
        ];
        $this->client         = Mockery::mock(Client::class);
        $this->spinupwp       = new SpinupWp('123', $this->client);
        $this->serverEndpoint = new ServerEndpoint($this->spinupwp);
    }

    public function test_resources_are_mapped(): void
    {
        $paginator = new Paginator($this->serverEndpoint, $this->payload['pagination']);
        $servers   = (new ResourceCollection($this->payload['data'], ServerResource::class, $this->spinupwp, $paginator));

        $this->assertInstanceOf(ServerResource::class, $servers->toArray()[0]);
    }

    public function test_resources_are_countable(): void
    {
        $paginator = new Paginator($this->serverEndpoint, $this->payload['pagination']);
        $servers   = new ResourceCollection($this->payload['data'], ServerResource::class, $this->spinupwp, $paginator);

        $this->assertEquals(3, $servers->count());
    }

    public function test_resources_are_arrayable(): void
    {
        $paginator = new Paginator($this->serverEndpoint, $this->payload['pagination']);
        $servers   = (new ResourceCollection($this->payload['data'], ServerResource::class, $this->spinupwp, $paginator))->toArray();

        $this->assertIsArray($servers);
        $this->assertEquals('hellfish-media', $servers[0]->name);
    }

    public function test_resources_can_be_iterated(): void
    {
        $this->payload['pagination']['next'] = 'https://api.spinupwp.app/v1/servers?page=2';

        $this->client->shouldReceive('request')->once()->with('GET', 'servers?page=2', [])->andReturn(
            new Response(200, [], '{"data": [{"name": "dev-hellfish-media"}], "pagination": {"previous": null, "next": null, "count": 3}}')
        );

        $paginator = new Paginator($this->serverEndpoint, $this->payload['pagination']);
        $servers   = (new ResourceCollection($this->payload['data'], ServerResource::class, $this->spinupwp, $paginator))->toArray();

        $this->assertCount(3, $servers);
        $this->assertEquals('dev-hellfish-media', $servers[2]->name);
    }
}

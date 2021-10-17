<?php

use DeliciousBrains\SpinupWp\Resources\Resource;
use DeliciousBrains\SpinupWp\SpinupWp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    public function test_resource_has_data(): void
    {
        $payload = [
            'data' => [
                'name'           => 'hellfish-media',
                'ubuntu_version' => '20.04',
            ],
        ];

        $spinupwp = new SpinupWp('123', Mockery::mock(Client::class));
        $resource = new Resource($payload, $spinupwp);

        $this->assertEquals('hellfish-media', $resource->name);
        $this->assertEquals('20.04', $resource->ubuntu_version);
        $this->assertIsArray($resource->toArray());
        $this->assertEquals('hellfish-media', $resource->toArray()['name']);
        $this->assertEquals('20.04', $resource->toArray()['ubuntu_version']);
    }

    public function test_resource_has_event(): void
    {
        $payload = [
            'event_id' => 100,
            'data'     => [],
        ];

        $client   = Mockery::mock(Client::class);
        $spinupwp = new SpinupWp('123', $client);
        $resource = new Resource($payload, $spinupwp);

        $client->shouldReceive('request')->once()->with('GET', 'events/100', [])->andReturn(
            new Response(200, [], '{"data": {"name": "Creating site hellfish.media"}}')
        );

        $this->assertEquals(100, $resource->eventId);
        $this->assertEquals('Creating site hellfish.media', $resource->event()->name);
    }
}
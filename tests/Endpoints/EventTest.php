<?php

use DeliciousBrains\SpinupWp\Endpoints\Event;
use DeliciousBrains\SpinupWp\SpinupWp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function test_get_request(): void
    {
        $client        = Mockery::mock(Client::class);
        $spinupwp      = new SpinupWp('123', $client);
        $eventEndpoint = new Event($spinupwp);

        $client->shouldReceive('request')->once()->with('GET', 'events/1', [])->andReturn(
            new Response(200, [], '{"data": {"name": "Creating site hellfish.media"}}')
        );

        $event = $eventEndpoint->get(1);
        $this->assertEquals('Creating site hellfish.media', $event->name);
    }

    public function test_list_request(): void
    {
        $client        = Mockery::mock(Client::class);
        $spinupwp      = new SpinupWp('123', $client);
        $eventEndpoint = new Event($spinupwp);

        $client->shouldReceive('request')->once()->with('GET', 'events?page=1', [])->andReturn(
            new Response(200, [], '{"data": [{"name": "Creating site hellfish.media"}, {"name": "Deleting site hellfish.media"}], "pagination": {"previous": null, "next": null, "count": 2}}')
        );

        $events = $eventEndpoint->list();
        $this->assertCount(2, $events);
    }

    public function test_list_request_with_pagination_parameters(): void
    {
        $client        = Mockery::mock(Client::class);
        $spinupwp      = new SpinupWp('123', $client);
        $eventEndpoint = new Event($spinupwp);

        $client->shouldReceive('request')->once()->with('GET', 'events?page=2&limit=100', [])->andReturn(
            new Response(200, [], '{"data": [{"name": "Creating site hellfish.media"}, {"name": "Deleting site hellfish.media"}], "pagination": {"previous": null, "next": null, "count": 2}}')
        );

        $events = $eventEndpoint->list(2, [
            'limit' => 100,
        ]);
        $this->assertCount(2, $events);
    }
}

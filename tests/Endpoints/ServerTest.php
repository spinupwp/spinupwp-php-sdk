<?php

use DeliciousBrains\SpinupWp\Endpoints\Server;
use DeliciousBrains\SpinupWp\SpinupWp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    public function test_get_request(): void
    {
        $client         = Mockery::mock(Client::class);
        $spinupwp       = new SpinupWp('123', $client);
        $serverEndpoint = new Server($spinupwp);

        $client->shouldReceive('request')->once()->with('GET', 'servers/1', [])->andReturn(
            new Response(200, [], '{"data": {"name": "hellfish-media"}}')
        );

        $server = $serverEndpoint->get(1);
        $this->assertEquals('hellfish-media', $server->name);
    }

    public function test_list_request(): void
    {
        $client         = Mockery::mock(Client::class);
        $spinupwp       = new SpinupWp('123', $client);
        $serverEndpoint = new Server($spinupwp);

        $client->shouldReceive('request')->once()->with('GET', 'servers?page=1', [])->andReturn(
            new Response(200, [], '{"data": [{"name": "hellfish-media"}, {"name": "staging-hellfish-media"}], "pagination": {"previous": null, "next": null, "count": 2}}')
        );

        $servers = $serverEndpoint->list();
        $this->assertCount(2, $servers);
    }

    public function test_server_sites_request(): void
    {
        $client         = Mockery::mock(Client::class);
        $spinupwp       = new SpinupWp('123', $client);
        $serverEndpoint = new Server($spinupwp);

        $client->shouldReceive('request')->once()->with('GET', 'servers/1', [])->andReturn(
            new Response(200, [], '{"data": {"id": 1, "name": "hellfish-media"}}')
        );

        $client->shouldReceive('request')->once()->with('GET', 'sites?server_id=1&page=1', [])->andReturn(
            new Response(200, [], '{"data": [{"domain": "hellfish.media"}, {"domain": "staging.hellfish.media"}], "pagination": {"previous": null, "next": null, "count": 2}}')
        );

        $server = $serverEndpoint->get(1);
        $this->assertEquals('hellfish-media', $server->name);

        $sites = $server->sites();
    }

    public function test_server_restart_request(): void
    {
        $client         = Mockery::mock(Client::class);
        $spinupwp       = new SpinupWp('123', $client);
        $serverEndpoint = new Server($spinupwp);

        $client->shouldReceive('request')->once()->with('POST', 'servers/1/reboot', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $serverEndpoint->reboot(1));
    }
}

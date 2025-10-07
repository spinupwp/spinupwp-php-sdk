<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SpinupWp\Endpoints\Server;
use SpinupWp\SpinupWp;

class ServerTest extends TestCase
{
    protected MockInterface $client;

    protected Server $serverEndpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Mockery::mock(Client::class);
        $spinupwp     = new SpinupWp('123', $this->client);

        $this->serverEndpoint = new Server($spinupwp);
    }

    public function test_get_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'servers/1', [])->andReturn(
            new Response(200, [], '{"data": {"name": "hellfish-media"}}')
        );

        $server = $this->serverEndpoint->get(1);
        $this->assertEquals('hellfish-media', $server->name);
    }

    public function test_list_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'servers?page=1', [])->andReturn(
            new Response(200, [], '{"data": [{"name": "hellfish-media"}, {"name": "staging-hellfish-media"}], "pagination": {"previous": null, "next": null, "count": 2}}')
        );

        $servers = $this->serverEndpoint->list();
        $this->assertCount(2, $servers);
    }

    public function test_create_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'servers', [
            'form_params' => [
                'hostname' => 'hellfish-media',
            ],
        ])->andReturn(
            new Response(200, [], '{"data": {"name": "hellfish-media"}}')
        );

        $server = $this->serverEndpoint->create(['hostname' => 'hellfish-media']);
        $this->assertEquals('hellfish-media', $server->name);
    }

    public function test_create_custom_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'servers/custom', [
            'form_params' => [
                'hostname' => 'hellfish-media',
            ],
        ])->andReturn(
            new Response(200, [], '{"data": {"name": "hellfish-media"}}')
        );

        $server = $this->serverEndpoint->createCustom(['hostname' => 'hellfish-media']);
        $this->assertEquals('hellfish-media', $server->name);
    }

    public function test_delete_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'servers/1', [
            'form_params' => [
                'delete_server_on_provider' => false,
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->serverEndpoint->delete(1));
    }

    public function test_server_sites_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'servers/1', [])->andReturn(
            new Response(200, [], '{"data": {"id": 1, "name": "hellfish-media"}}')
        );

        $this->client->shouldReceive('request')->once()->with('GET', 'sites?page=1&server_id=1', [])->andReturn(
            new Response(200, [], '{"data": [{"domain": "hellfish.media"}, {"domain": "staging.hellfish.media"}], "pagination": {"previous": null, "next": null, "count": 2}}')
        );

        $server = $this->serverEndpoint->get(1);
        $this->assertEquals('hellfish-media', $server->name);

        $sites = $server->sites();
    }

    public function test_server_restart_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'servers/1/reboot', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->serverEndpoint->reboot(1));
    }

    public function test_restart_nginx(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'servers/1/services/nginx/restart', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->serverEndpoint->restartNginx(1));
    }

    public function test_restart_redis(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'servers/1/services/redis/restart', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->serverEndpoint->restartRedis(1));
    }

    public function test_restart_php(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'servers/1/services/php/restart', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->serverEndpoint->restartPhp(1));
    }

    public function test_restart_mysql(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'servers/1/services/mysql/restart', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->serverEndpoint->restartMysql(1));
    }
}

<?php

use DeliciousBrains\SpinupWp\Endpoints\Site;
use DeliciousBrains\SpinupWp\Exceptions\NotFoundException;
use DeliciousBrains\SpinupWp\Exceptions\RateLimitException;
use DeliciousBrains\SpinupWp\Exceptions\UnauthorizedException;
use DeliciousBrains\SpinupWp\Exceptions\ValidationException;
use DeliciousBrains\SpinupWp\SpinupWp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class SiteTest extends TestCase
{
    public SpinupWp $spinupwp;
    public Site $siteEndpoint;
    public Client $client;

    public function setUp(): void
    {
        $this->spinupwp     = Mockery::mock(SpinupWp::class);
        $this->client       = Mockery::mock(Client::class);
        $this->siteEndpoint = new Site($this->client, $this->spinupwp);
    }

    public function test_get_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'sites/1', [])->andReturn(
            new Response(200, [], '{"data": {"domain": "hellfish.media"}}')
        );

        $site = $this->siteEndpoint->get(1);
        $this->assertEquals('hellfish.media', $site->domain);
    }

    public function test_list_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'sites?page=1', [])->andReturn(
            new Response(200, [], '{"data": [{"domain": "hellfish.media"}, {"domain": "staging.hellfish.media"}], "pagination": {"previous": null, "next": null, "count": 2}}')
        );

        $sites = $this->siteEndpoint->list();
        $this->assertCount(2, $sites);
    }

    public function test_create_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites', [
            'form_params' => [
                'domain'    => 'hellfish.media',
                'server_id' => 1,
            ]
        ])->andReturn(
            new Response(200, [], '{"data": {"domain": "hellfish.media"}}')
        );

        $site = $this->siteEndpoint->create(1, ['domain' => 'hellfish.media']);
        $this->assertEquals('hellfish.media', $site->domain);
    }

    public function test_delete_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->delete(1));
    }

    public function test_handling_validation_errors(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites', [
            'form_params' => [
                'server_id' => 1,
            ]
        ])->andReturn(
            new Response(422, [], '{"domain": ["The domain is required."]}')
        );

        try {
            (new Site($this->client, $this->spinupwp))->create(1, []);
        } catch (ValidationException $e) {
            //
        }

        $this->assertEquals(['domain' => ['The domain is required.']], $e->errors());
    }

    public function test_handling_404_errors(): void
    {
        $this->expectException(NotFoundException::class);

        $this->client->shouldReceive('request')->once()->with('GET', 'sites/1', [])->andReturn(
            new Response(404)
        );

        (new Site($this->client, $this->spinupwp))->get(1);
    }

    public function test_handling_401_errors(): void
    {
        $this->expectException(UnauthorizedException::class);

        $this->client->shouldReceive('request')->once()->with('GET', 'sites/1', [])->andReturn(
            new Response(401)
        );

        (new Site($this->client, $this->spinupwp))->get(1);
    }

    public function test_handling_429_errors(): void
    {
        $this->expectException(RateLimitException::class);

        $this->client->shouldReceive('request')->once()->with('GET', 'sites/1', [])->andReturn(
            new Response(429)
        );

        (new Site($this->client, $this->spinupwp))->get(1);
    }
}
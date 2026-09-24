<?php

namespace Endpoints;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use SpinupWp\Endpoints\DnsProvider;
use SpinupWp\Resources\DnsProvider as DnsProviderResource;
use SpinupWp\Resources\ResourceCollection;
use SpinupWp\SpinupWp;

class DnsProviderTest extends TestCase
{
    public SpinupWp $spinupwp;

    public DnsProvider $endpoint;

    public Client $client;

    public function setUp(): void
    {
        $this->client   = Mockery::mock(Client::class);
        $this->spinupwp = new SpinupWp('123', $this->client);
        $this->endpoint = new DnsProvider($this->spinupwp);
    }

    public function test_list_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'dns-providers?page=1', [])->andReturn(
            new Response(200, [], '{"data": [{"id": 12, "service": "cloudflare", "name": "Cloudflare"}], "pagination": {"previous": null, "next": null, "count": 1}}')
        );

        $dnsProviders = $this->endpoint->list();
        $this->assertInstanceOf(ResourceCollection::class, $dnsProviders);
        $this->assertCount(1, $dnsProviders);
        $this->assertInstanceOf(DnsProviderResource::class, $dnsProviders->toArray()[0]);
        $this->assertEquals('cloudflare', $dnsProviders->toArray()[0]->service);
    }

    public function test_list_request_with_pagination_parameters(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'dns-providers?page=2&limit=100', [])->andReturn(
            new Response(200, [], '{"data": [{"id": 12, "service": "cloudflare"}], "pagination": {"previous": null, "next": null, "count": 1}}')
        );

        $dnsProviders = $this->endpoint->list(2, ['limit' => 100]);
        $this->assertCount(1, $dnsProviders);
    }
}

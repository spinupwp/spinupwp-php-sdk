<?php

namespace Endpoints;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use SpinupWp\Endpoints\Site;
use SpinupWp\Endpoints\SshKey;
use SpinupWp\Exceptions\NotFoundException;
use SpinupWp\Exceptions\RateLimitException;
use SpinupWp\Exceptions\UnauthorizedException;
use SpinupWp\Exceptions\ValidationException;
use SpinupWp\Resources\Event as EventResource;
use SpinupWp\Resources\ResourceCollection;
use SpinupWp\Resources\SshKey as SshKeyResource;
use SpinupWp\SpinupWp;

class SshKeyTest extends TestCase
{
    public SpinupWp $spinupwp;

    public SshKey $endpoint;

    public Client $client;

    public function setUp(): void
    {
        $this->client   = Mockery::mock(Client::class);
        $this->spinupwp = new SpinupWp('123', $this->client);
        $this->endpoint = new SshKey($this->spinupwp);
    }

    public function test_get_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'ssh-key', [])->andReturn(
            new Response(200, [], '{"key": "ssh-rsa ..."}')
        );

        $key = $this->endpoint->get();
        $this->assertEquals('ssh-rsa ...', $key);
    }

    public function test_list_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'ssh-keys?page=1', [])->andReturn(
            new Response(200, [], '{"data": [{"id": 1, "name": "Vincent\'s laptop", "fingerprint": "SHA256:...", "publickey": "ssh-rsa ..."}], "pagination": {"previous": null, "next": null, "count": 1}}')
        );

        $sshKeys = $this->endpoint->list();
        $this->assertInstanceOf(ResourceCollection::class, $sshKeys);
        $this->assertCount(1, $sshKeys);
        $this->assertInstanceOf(SshKeyResource::class, $sshKeys->toArray()[0]);
        $this->assertEquals('Vincent\'s laptop', $sshKeys->toArray()[0]->name);
    }

    public function test_list_request_with_pagination_parameters(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'ssh-keys?page=2&limit=100', [])->andReturn(
            new Response(200, [], '{"data": [{"id": 1, "name": "Vincent\'s laptop"}], "pagination": {"previous": null, "next": null, "count": 1}}')
        );

        $sshKeys = $this->endpoint->list(2, ['limit' => 100]);
        $this->assertCount(1, $sshKeys);
    }
}

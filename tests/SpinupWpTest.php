<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use SpinupWp\Endpoints\Server;
use SpinupWp\Endpoints\Site;
use SpinupWp\Endpoints\SshKey;
use SpinupWp\SpinupWp;

class SpinupWpTest extends TestCase
{
    public function test_has_servers_endpoint(): void
    {
        $spinupwp = new SpinupWp('123', Mockery::mock(Client::class));

        $this->assertInstanceOf(Server::class, $spinupwp->servers);
    }

    public function test_has_sites_endpoint(): void
    {
        $spinupwp = new SpinupWp('123', Mockery::mock(Client::class));

        $this->assertInstanceOf(Site::class, $spinupwp->sites);
    }

    public function test_has_ssh_keys_endpoint(): void
    {
        $spinupwp = new SpinupWp('123', Mockery::mock(Client::class));

        $this->assertInstanceOf(SshKey::class, $spinupwp->sshKeys);
    }
}

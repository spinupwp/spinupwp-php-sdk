<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SpinupWp\Endpoints\Site;
use SpinupWp\Exceptions\NotFoundException;
use SpinupWp\Exceptions\RateLimitException;
use SpinupWp\Exceptions\UnauthorizedException;
use SpinupWp\Exceptions\ValidationException;
use SpinupWp\Resources\Event as EventResource;
use SpinupWp\SpinupWp;

class SiteTest extends TestCase
{
    public SpinupWp $spinupwp;

    public Site $siteEndpoint;

    public Client $client;

    public function setUp(): void
    {
        $this->client       = Mockery::mock(Client::class);
        $this->spinupwp     = new SpinupWp('123', $this->client);
        $this->siteEndpoint = new Site($this->spinupwp);
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

    public function test_listForServer_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'sites?page=1&server_id=1', [])->andReturn(
            new Response(200, [], '{"data": [{"domain": "hellfish.media"}, {"domain": "staging.hellfish.media"}], "pagination": {"previous": null, "next": null, "count": 2}}')
        );

        $sites = $this->siteEndpoint->listForServer(1);
        $this->assertCount(2, $sites);
    }

    public function test_listForServer_request_with_pagination_parameters(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'sites?page=2&server_id=1&limit=100', [])->andReturn(
            new Response(200, [], '{"data": [{"domain": "hellfish.media"}, {"domain": "staging.hellfish.media"}], "pagination": {"previous": null, "next": null, "count": 2}}')
        );

        $sites = $this->siteEndpoint->listForServer(1, 2, [
            'limit' => 100,
        ]);
        $this->assertCount(2, $sites);
    }

    public function test_create_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites', [
            'form_params' => [
                'domain'    => 'hellfish.media',
                'server_id' => 1,
            ],
        ])->andReturn(
            new Response(200, [], '{"data": {"domain": "hellfish.media"}}')
        );

        $site = $this->siteEndpoint->create(1, ['domain' => 'hellfish.media']);
        $this->assertEquals('hellfish.media', $site->domain);
    }

    public function test_delete_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1', [
            'form_params' => [
                'delete_database' => false,
                'delete_backups'  => false,
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->delete(1));
    }

    public function test_git_deploy_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/git/deploy', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->gitDeploy(1));
    }

    public function test_purge_page_cache_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/page-cache/purge', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->purgePageCache(1));
    }

    public function test_purge_object_cache_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/object-cache/purge', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->purgeObjectCache(1));
    }

    public function test_correct_file_permissions(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/file-permissions/correct', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->correctFilePermissions(1));
    }

    public function test_handling_validation_errors(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites', [
            'form_params' => [
                'server_id' => 1,
            ],
        ])->andReturn(
            new Response(422, [], '{"domain": ["The domain is required."]}')
        );

        try {
            (new Site($this->spinupwp))->create(1, []);
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

        (new Site($this->spinupwp))->get(1);
    }

    public function test_handling_401_errors(): void
    {
        $this->expectException(UnauthorizedException::class);

        $this->client->shouldReceive('request')->once()->with('GET', 'sites/1', [])->andReturn(
            new Response(401)
        );

        (new Site($this->spinupwp))->get(1);
    }

    public function test_handling_429_errors(): void
    {
        $this->expectException(RateLimitException::class);

        $this->client->shouldReceive('request')->once()->with('GET', 'sites/1', [])->andReturn(
            new Response(429)
        );

        (new Site($this->spinupwp))->get(1);
    }

    public function test_enable_https_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/https', [
            'form_params' => [
                'type' => 'webroot',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->enableHttps(1, ['type' => 'webroot']));
    }

    public function test_update_https_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PUT', 'sites/1/https', [
            'form_params' => [
                'type'        => 'custom',
                'certificate' => '-----BEGIN CERTIFICATE-----',
                'private_key' => '-----BEGIN PRIVATE KEY-----',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->updateHttps(1, [
            'type'        => 'custom',
            'certificate' => '-----BEGIN CERTIFICATE-----',
            'private_key' => '-----BEGIN PRIVATE KEY-----',
        ]));
    }

    public function test_disable_https_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1/https', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->disableHttps(1));
    }

    public function test_update_php_version_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PUT', 'sites/1/php', [
            'form_params' => [
                'php_version' => '8.3',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->updatePhpSettings(1, ['php_version' => '8.3']));
    }

    public function test_enable_spinupwp_subdomain_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/spinupwp-subdomain', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->enableSpinupwpSubdomain(1));
    }

    public function test_disable_spinupwp_subdomain_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1/spinupwp-subdomain', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->disableSpinupwpSubdomain(1));
    }

    public function test_list_domains_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'sites/1/domains', [])->andReturn(
            new Response(200, [], '{"data": [{"id": 1, "domain": "www.hellfish.media"}]}')
        );

        $domains = $this->siteEndpoint->listDomains(1);
        $this->assertCount(1, $domains);
        $this->assertEquals('www.hellfish.media', $domains[0]['domain']);
    }

    public function test_add_domain_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/domains', [
            'form_params' => [
                'domain' => 'www.hellfish.media',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100, "data": {"id": 1, "domain": "www.hellfish.media"}}')
        );

        $result = $this->siteEndpoint->addDomain(1, ['domain' => 'www.hellfish.media']);
        $this->assertEquals(100, $result['event_id']);
        $this->assertEquals('www.hellfish.media', $result['data']['domain']);
    }

    public function test_update_domain_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PUT', 'sites/1/domains/2', [
            'form_params' => [
                'redirect' => [
                    'enabled'     => true,
                    'type'        => 301,
                    'destination' => 'hellfish.media',
                ],
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100, "data": {"id": 2, "domain": "www.hellfish.media"}}')
        );

        $result = $this->siteEndpoint->updateDomain(1, 2, [
            'redirect' => [
                'enabled'     => true,
                'type'        => 301,
                'destination' => 'hellfish.media',
            ],
        ]);
        $this->assertEquals(100, $result['event_id']);
    }

    public function test_delete_domain_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1/domains/2', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->deleteDomain(1, 2));
    }

    public function test_connect_git_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/git', [
            'form_params' => [
                'repo'           => 'git@github.com:spinupwp/spinupwp-composer-site.git',
                'branch'         => 'main',
                'push_to_deploy' => true,
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->connectGit(1, [
            'repo'           => 'git@github.com:spinupwp/spinupwp-composer-site.git',
            'branch'         => 'main',
            'push_to_deploy' => true,
        ]));
    }

    public function test_update_git_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PATCH', 'sites/1/git', [
            'form_params' => [
                'branch' => 'production',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->updateGit(1, ['branch' => 'production']));
    }

    public function test_disconnect_git_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1/git', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->disconnectGit(1));
    }

    public function test_enable_page_cache_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/page-cache', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->enablePageCache(1));
    }

    public function test_update_page_cache_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PATCH', 'sites/1/page-cache', [
            'form_params' => [
                'duration'      => 1,
                'duration_unit' => 'h',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->updatePageCache(1, [
            'duration'      => 1,
            'duration_unit' => 'h',
        ]));
    }

    public function test_disable_page_cache_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1/page-cache', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->disablePageCache(1));
    }

    public function test_update_nginx_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PATCH', 'sites/1/nginx', [
            'form_params' => [
                'uploads_directory_protected' => true,
                'xmlrpc_protected'            => true,
            ],
        ])->andReturn(
            new Response(200, [], '{"data": {"domain": "hellfish.media"}}')
        );

        $site = $this->siteEndpoint->updateNginx(1, [
            'uploads_directory_protected' => true,
            'xmlrpc_protected'            => true,
        ]);
        $this->assertEquals('hellfish.media', $site->domain);
    }

    public function test_enable_cron_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/cron', [
            'form_params' => [
                'interval' => 5,
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->enableCron(1, ['interval' => 5]));
    }

    public function test_update_cron_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PUT', 'sites/1/cron', [
            'form_params' => [
                'interval' => 15,
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->updateCron(1, ['interval' => 15]));
    }

    public function test_disable_cron_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1/cron', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->disableCron(1));
    }

    public function test_enable_basic_auth_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/basic-auth', [
            'form_params' => [
                'username' => 'turnipjuice',
                'password' => 'DK6Jrfj8gyWzL',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->enableBasicAuth(1, [
            'username' => 'turnipjuice',
            'password' => 'DK6Jrfj8gyWzL',
        ]));
    }

    public function test_update_basic_auth_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PATCH', 'sites/1/basic-auth', [
            'form_params' => [
                'username' => 'newuser',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->updateBasicAuth(1, ['username' => 'newuser']));
    }

    public function test_disable_basic_auth_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1/basic-auth', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->disableBasicAuth(1));
    }

    public function test_list_path_redirects_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('GET', 'sites/1/path-redirects', [])->andReturn(
            new Response(200, [], '{"data": [{"id": 1, "from": "/old", "to": "/new", "type": "permanent"}]}')
        );

        $redirects = $this->siteEndpoint->listPathRedirects(1);
        $this->assertCount(1, $redirects);
        $this->assertEquals('/old', $redirects[0]['from']);
    }

    public function test_add_path_redirect_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('POST', 'sites/1/path-redirects', [
            'form_params' => [
                'from' => '/old-path',
                'to'   => '/new-path',
                'type' => 'permanent',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->addPathRedirect(1, [
            'from' => '/old-path',
            'to'   => '/new-path',
            'type' => 'permanent',
        ]));
    }

    public function test_update_path_redirect_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PATCH', 'sites/1/path-redirects/2', [
            'form_params' => [
                'to' => '/newer-path',
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->updatePathRedirect(1, 2, ['to' => '/newer-path']));
    }

    public function test_delete_path_redirect_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('DELETE', 'sites/1/path-redirects/2', [])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->deletePathRedirect(1, 2));
    }

    public function test_update_backup_settings_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PATCH', 'sites/1/backup-settings', [
            'form_params' => [
                'storage_provider_id'     => 1,
                'storage_provider_bucket' => 'turnipjuice-media',
                'storage_provider_region' => 'nyc3',
            ],
        ])->andReturn(
            new Response(200, [], '{"data": {"domain": "hellfish.media"}}')
        );

        $site = $this->siteEndpoint->updateBackupSettings(1, [
            'storage_provider_id'     => 1,
            'storage_provider_bucket' => 'turnipjuice-media',
            'storage_provider_region' => 'nyc3',
        ]);
        $this->assertEquals('hellfish.media', $site->domain);
    }

    public function test_update_backup_schedule_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PATCH', 'sites/1/backup-schedule', [
            'form_params' => [
                'daily_schedule' => [
                    'time_of_day'      => [2],
                    'backup_database'  => true,
                    'backup_files'     => true,
                    'retention_period' => 30,
                ],
            ],
        ])->andReturn(
            new Response(200, [], '{"data": {"domain": "hellfish.media"}}')
        );

        $site = $this->siteEndpoint->updateBackupSchedule(1, [
            'daily_schedule' => [
                'time_of_day'      => [2],
                'backup_database'  => true,
                'backup_files'     => true,
                'retention_period' => 30,
            ],
        ]);
        $this->assertEquals('hellfish.media', $site->domain);
    }

    public function test_update_site_user_request(): void
    {
        $this->client->shouldReceive('request')->once()->with('PUT', 'sites/1/site-user', [
            'form_params' => [
                'authentication' => 'publickey',
                'ssh_key_ids'    => [1],
            ],
        ])->andReturn(
            new Response(200, [], '{"event_id": 100}')
        );

        $this->assertEquals(100, $this->siteEndpoint->updateSiteUser(1, [
            'authentication' => 'publickey',
            'ssh_key_ids'    => [1],
        ]));
    }
}

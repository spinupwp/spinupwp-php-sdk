# SpinupWP PHP SDK

[![Tests](https://github.com/spinupwp/spinupwp-php-sdk/actions/workflows/tests.yml/badge.svg?event=push)](https://github.com/spinupwp/spinupwp-php-sdk/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/spinupwp/spinupwp-php-sdk)](https://packagist.org/packages/spinupwp/spinupwp-php-sdk)
[![Latest Stable Version](https://img.shields.io/packagist/v/spinupwp/spinupwp-php-sdk)](https://packagist.org/packages/spinupwp/spinupwp-php-sdk)
[![License](https://img.shields.io/packagist/l/spinupwp/spinupwp-php-sdk)](https://packagist.org/packages/spinupwp/spinupwp-php-sdk)

The SpinupWP PHP SDK provides an expressive interface for interacting with [SpinupWP's API](https://api.spinupwp.com). It includes a pre-defined set of classes for API resources that initialize themselves dynamically from API responses.

## Installation
To get started, require the package via [Composer](https://getcomposer.org):
```bash
composer require spinupwp/spinupwp-php-sdk
```

## Usage
You can create an instance of the SpinupWP client like so:
```php
$spinupwp = new SpinupWp\SpinupWp('API_TOKEN');
```

### Servers
```php
// Return a collection of servers
$servers = $spinupwp->servers->list();

// Return a single server
$server = $spinupwp->servers->get($serverId);

// Create and return a new server 
$server = $spinupwp->servers->create([]);

// Create and return a new custom server 
$server = $spinupwp->servers->createCustom([]);

// Delete a server
$eventId = $spinupwp->servers->delete($serverId, $deleteOnProvider);

// Reboot a server
$eventId = $spinupwp->servers->reboot($serverId);

// Restart the Nginx service on a server
$eventId = $spinupwp->servers->restartNginx($serverId);

// Restart the Redis service on a server
$eventId = $spinupwp->servers->restartRedis($serverId);

// Restart all versions of the PHP-FPM service installed on a server
$eventId = $spinupwp->servers->restartPhp($serverId);

// Restart the MySQL or MariaDB service on a server
$eventId = $spinupwp->servers->restartMysql($serverId);
```
On a `Server` instance you may also call:
```php
// Return a collection of this server's sites
$sites = $server->sites();

// Delete the current server
$server->delete($deleteOnProvider);

// Reboot the current server
$server->reboot();

// Restart the Nginx service on the current server
$server->restartNginx();

// Restart the Redis service on the current server
$server->restartRedis();

// Restart all versions of the PHP-FPM service installed on the current server
$server->restartPhp();

// Restart the MySQL or MariaDB service on the current server
$server->restartMysql();
```

### Sites
```php
// Return a collection of sites
$sites = $spinupwp->sites->list();

// Return a single site
$site = $spinupwp->sites->get($siteId);

// Create and return a new site
$site = $spinupwp->sites->create($serverId, []);

// Delete a site
$eventId = $spinupwp->sites->delete($siteId);

// Run a git deployment
$eventId = $spinupwp->sites->gitDeploy($siteId);

// Purge a site's page cache
$eventId = $spinupwp->sites->purgePageCache($siteId);

// Purge a site's object cache
$eventId = $spinupwp->sites->purgeObjectCache($siteId);

// Reset a site's file permissions
$eventId = $spinupwp->sites->correctFilePermissions($siteId);

// Enable HTTPS
$eventId = $spinupwp->sites->enableHttps($siteId, ['type' => 'webroot']);

// Update HTTPS settings
$eventId = $spinupwp->sites->updateHttps($siteId, [
    'type' => 'custom',
    'certificate' => '-----BEGIN CERTIFICATE-----...',
    'private_key' => '-----BEGIN PRIVATE KEY-----...',
]);

// Disable HTTPS
$eventId = $spinupwp->sites->disableHttps($siteId);

// Update PHP version
$eventId = $spinupwp->sites->updatePhpSettings($siteId, ['php_version' => '8.3']);

// Enable the SpinupWP subdomain
$eventId = $spinupwp->sites->enableSpinupwpSubdomain($siteId);

// Disable the SpinupWP subdomain
$eventId = $spinupwp->sites->disableSpinupwpSubdomain($siteId);

// List additional domains
$domains = $spinupwp->sites->listDomains($siteId);

// Add an additional domain
$domain = $spinupwp->sites->addDomain($siteId, [
    'domain' => 'www.turnipjuice.media',
    'redirect' => [
        'enabled' => true,
    ],
]);

// Update an additional domain
$domain = $spinupwp->sites->updateDomain($siteId, $domainId, [
    'redirect' => [
        'enabled' => true,
        'type' => 301,
        'destination' => 'turnipjuice.media',
    ],
]);

// Delete an additional domain
$eventId = $spinupwp->sites->deleteDomain($siteId, $domainId);

// Connect a Git repository
$eventId = $spinupwp->sites->connectGit($siteId, [
    'repo' => 'git@github.com:spinupwp/spinupwp-composer-site.git',
    'branch' => 'main',
    'push_to_deploy' => true,
]);

// Connect a Git repository using a custom site deploy key
$eventId = $spinupwp->sites->connectGit($siteId, [
    'repo' => 'git@github.com:spinupwp/spinupwp-composer-site.git',
    'branch' => 'main',
    'deploy_key' => [
        'privatekey' => '-----BEGIN OPENSSH PRIVATE KEY-----...',
        'publickey' => 'ssh-ed25519 AAAA...',
    ],
]);

// Update Git settings
$eventId = $spinupwp->sites->updateGit($siteId, ['branch' => 'production']);

// Disconnect Git
$eventId = $spinupwp->sites->disconnectGit($siteId);

// Enable page cache
$eventId = $spinupwp->sites->enablePageCache($siteId);

// Update page cache settings
$eventId = $spinupwp->sites->updatePageCache($siteId, [
    'duration' => 1,
    'duration_unit' => 'h',
]);

// Disable page cache
$eventId = $spinupwp->sites->disablePageCache($siteId);

// Update Nginx settings
$eventIds = $spinupwp->sites->updateNginx($siteId, [
    'uploads_directory_protected' => true,
    'xmlrpc_protected' => true,
]);

// Enable the server-level WP cron
$eventId = $spinupwp->sites->enableCron($siteId, ['interval' => 5]);

// Update the WP cron interval
$eventId = $spinupwp->sites->updateCron($siteId, ['interval' => 15]);

// Disable the server-level WP cron
$eventId = $spinupwp->sites->disableCron($siteId);

// Enable basic auth
$eventId = $spinupwp->sites->enableBasicAuth($siteId, [
    'username' => 'turnipjuice',
    'password' => 'DK6Jrfj8gyWzL',
]);

// Update basic auth credentials
$eventId = $spinupwp->sites->updateBasicAuth($siteId, ['username' => 'newuser']);

// Disable basic auth
$eventId = $spinupwp->sites->disableBasicAuth($siteId);

// List path redirects
$redirects = $spinupwp->sites->listPathRedirects($siteId);

// Add a path redirect
$eventId = $spinupwp->sites->addPathRedirect($siteId, [
    'from' => '/old-path',
    'to' => '/new-path',
    'type' => 'permanent',
]);

// Delete a path redirect (identified by its from/to)
$eventId = $spinupwp->sites->deletePathRedirect($siteId, [
    'from' => '/old-path',
    'to'   => '/new-path',
]);

// Update backup settings
$site = $spinupwp->sites->updateBackupSettings($siteId, [
    'storage_provider_id' => 1,
    'storage_provider_bucket' => 'turnipjuice-media',
    'storage_provider_region' => 'nyc3',
]);

// Update backup schedule
$site = $spinupwp->sites->updateBackupSchedule($siteId, [
    'daily_schedule' => [
        'time_of_day' => [2],
        'backup_database' => true,
        'backup_files' => true,
        'retention_period' => 30,
    ],
]);

// Update site user authentication
$eventId = $spinupwp->sites->updateSiteUser($siteId, [
    'authentication' => 'publickey',
    'ssh_key_ids' => [1],
]);
```
On a `Site` instance you may also call:
```php
// Delete the current site
$site->delete();

// Run a git deployment
$site->gitDeploy();

// Purge a site's page cache
$site->purgePageCache();

// Purge a site's object cache
$site->purgeObjectCache();

// Reset a site's file permissions
$site->correctFilePermissions();

// Enable HTTPS
$site->enableHttps(['type' => 'webroot']);

// Update HTTPS settings
$site->updateHttps(['type' => 'custom', 'certificate' => '...', 'private_key' => '...']);

// Disable HTTPS
$site->disableHttps();

// Update PHP version
$site->updatePhpSettings(['php_version' => '8.3']);

// Enable the SpinupWP subdomain
$site->enableSpinupwpSubdomain();

// Disable the SpinupWP subdomain
$site->disableSpinupwpSubdomain();

// List additional domains
$site->listDomains();

// Add an additional domain
$site->addDomain(['domain' => 'www.turnipjuice.media']);

// Update an additional domain
$site->updateDomain($domainId, ['redirect' => ['enabled' => true]]);

// Delete an additional domain
$site->deleteDomain($domainId);

// Connect a Git repository
$site->connectGit(['repo' => '...', 'branch' => 'main']);

// Update Git settings
$site->updateGit(['branch' => 'production']);

// Disconnect Git
$site->disconnectGit();

// Enable page cache
$site->enablePageCache();

// Update page cache settings
$site->updatePageCache(['duration' => 1, 'duration_unit' => 'h']);

// Disable page cache
$site->disablePageCache();

// Update Nginx settings
$site->updateNginx(['uploads_directory_protected' => true]);

// Enable, update, or disable the server-level WP cron
$site->enableCron(['interval' => 5]);
$site->updateCron(['interval' => 15]);
$site->disableCron();

// Enable, update, or disable basic auth
$site->enableBasicAuth(['username' => 'turnipjuice', 'password' => 'DK6Jrfj8gyWzL']);
$site->updateBasicAuth(['username' => 'newuser']);
$site->disableBasicAuth();

// Manage path redirects
$site->listPathRedirects();
$site->addPathRedirect(['from' => '/old', 'to' => '/new', 'type' => 'permanent']);
$site->deletePathRedirect(['from' => '/old', 'to' => '/new']);

// Update backup settings
$site->updateBackupSettings(['storage_provider_id' => 1, 'storage_provider_bucket' => 'bucket']);

// Update backup schedule
$site->updateBackupSchedule(['daily_schedule' => ['time_of_day' => [2]]]);

// Update site user authentication
$site->updateSiteUser(['authentication' => 'publickey', 'ssh_key_ids' => [1]]);
```

### Events
```php
// Return a collection of events
$events = $spinupwp->events->list();

// Return a single event
$event = $spinupwp->events->get($eventId);
```

### SSH Key
```php
// Return SpinupWP's SSH Public Key
$key = $spinupwp->sshKeys->get();

// List the SSH keys configured on your team
$sshKeys = $spinupwp->sshKeys->list();
```

### Storage Providers
```php
// List the storage providers configured on your team
$storageProviders = $spinupwp->storageProviders->list();
```

### Resource Collections
When retrieving a list of resources, an instance of `ResourceCollection` is returned. This class handles fetching large lists of resources without having to paginate results and perform subsequent requests manually.
```php
$servers = $spinupwp->servers->list();

// Return an array of all servers
$servers->toArray();

// Return the total number of servers
$servers->count();

// Lazily iterate over all servers
foreach ($servers as $server) {
    // Do something with $server
}
```

## License
SpinupWP PHP SDK is open-sourced software licensed under the [MIT license](LICENSE.md).

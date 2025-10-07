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
````

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

# SpinupWP PHP SDK

[![Tests](https://github.com/deliciousbrains/spinupwp-php-sdk/actions/workflows/tests.yml/badge.svg?event=push)](https://github.com/deliciousbrains/spinupwp-php-sdk/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/deliciousbrains/spinupwp-php-sdk)](https://packagist.org/packages/deliciousbrains/spinupwp-php-sdk)
[![Latest Stable Version](https://img.shields.io/packagist/v/deliciousbrains/spinupwp-php-sdk)](https://packagist.org/packages/deliciousbrains/spinupwp-php-sdk)
[![License](https://img.shields.io/packagist/l/deliciousbrains/spinupwp-php-sdk)](https://packagist.org/packages/deliciousbrains/spinupwp-php-sdk)

The SpinupWP PHP SDK provides an expressive interface for interacting with [SpinupWP's API](https://api.spinupwp.com). It includes a pre-defined set of classes for API resources that initialize themselves dynamically from API responses.

## Installation
To get started, require the package via [Composer](https://getcomposer.org):
```bash
composer require deliciousbrains/spinupwp-php-sdk
```

## Usage
You can create an instance of the SpinupWP client like so:
```php
$spinupwp = new DeliciousBrains\SpinupWp\SpinupWp('API_TOKEN');
```

### Servers
```php
// Return a collection of servers
$servers = $spinupwp->servers->list();

// Return a single server
$server = $spinupwp->servers->get($serverId);
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
```
On a `Site` instance you may also call:
```php
// Delete the current site
$site->delete();
````

### Events
```php
// Return a single event
$event = $spinupwp->events->get($eventId);
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
SpinupWP PHP SDK is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
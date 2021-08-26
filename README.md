# SpinupWP PHP SDK

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
// Return an array of servers
$servers = $spinupwp->servers->all();

// Return a single server
$server = $spinupwp->servers->get($serverId);
```

### Sites
```php
// Return an array of sites
$sites = $spinupwp->sites->all();

// Return a single site
$site = $spinupwp->sites->get($siteId);

// Create and return a new site 
$site = $spinupwp->sites->create($serverId, []);

// Delete a site
$spinupwp->sites->delete($siteId);
```
On a `Site` instance you may also call:
```php
$site = $spinupwp->sites->get($siteId);

// Delete the current site
$site->delete();
````

### Events
```php
// Return a single event
$event = $spinupwp->events->get($eventId);
```
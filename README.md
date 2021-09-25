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
// Return a collection of servers
$servers = $spinupwp->servers->all();

// Return a single server
$server = $spinupwp->servers->get($serverId);
```

### Sites
```php
// Return a collection of sites
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

### Resource Collections
When retrieving a list of resources, an instance of `ResourceCollection` is returned. This class handles fetching large lists of resources without having to paginate results and perform subsequent requests manually.
```php
$servers = $spinupwp->servers->all();

// Return an array of all servers
$servers->toArray();

// Return the total number of servers
$servers->count();

// Lazily iterate over all servers
foreach ($servers as $server) {
    // Do something with $server
}
```
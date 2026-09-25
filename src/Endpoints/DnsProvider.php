<?php

namespace SpinupWp\Endpoints;

use SpinupWp\Resources\DnsProvider as DnsProviderResource;
use SpinupWp\Resources\ResourceCollection;

class DnsProvider extends Endpoint
{
    public function list(int $page = 1, array $parameters = []): ResourceCollection
    {
        $dnsProviders = $this->getRequest('dns-providers', array_merge([
            'page' => $page,
        ], $parameters));

        return $this->transformCollection(
            $dnsProviders['data'],
            DnsProviderResource::class,
            $this->getPaginator($dnsProviders['pagination'], $parameters),
        );
    }
}

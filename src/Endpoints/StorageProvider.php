<?php

namespace SpinupWp\Endpoints;

use SpinupWp\Resources\ResourceCollection;
use SpinupWp\Resources\StorageProvider as StorageProviderResource;

class StorageProvider extends Endpoint
{
    public function list(int $page = 1, array $parameters = []): ResourceCollection
    {
        $storageProviders = $this->getRequest('storage-providers', array_merge([
            'page' => $page,
        ], $parameters));

        return $this->transformCollection(
            $storageProviders['data'],
            StorageProviderResource::class,
            $this->getPaginator($storageProviders['pagination'], $parameters),
        );
    }
}

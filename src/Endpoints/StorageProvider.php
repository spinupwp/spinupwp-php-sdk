<?php

namespace SpinupWp\Endpoints;

class StorageProvider extends Endpoint
{
    public function list(): array
    {
        $storageProviders = $this->getRequest('storage-providers');

        return $storageProviders['data'];
    }
}

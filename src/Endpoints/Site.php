<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\Site as SiteResource;

class Site extends Endpoint
{
    public function get(int $id): SiteResource
    {
        $server = $this->getRequest("sites/{$id}");

        return new SiteResource($server, $this);
    }

    public function delete(int $id): void
    {
        $this->deleteRequest("sites/{$id}");
    }
}
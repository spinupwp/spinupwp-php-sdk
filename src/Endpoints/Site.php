<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\Site as SiteResource;

class Site extends Endpoint
{
    public function get(int $id): SiteResource
    {
        $site = $this->getRequest("sites/{$id}");

        return new SiteResource($site['data'], $this);
    }

    public function delete(int $id): void
    {
        $this->deleteRequest("sites/{$id}");
    }
}
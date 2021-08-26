<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\Server as ServerResource;

class Server extends Endpoint
{
    public function get(int $id): ServerResource
    {
        $server = $this->getRequest("servers/{$id}");

        return new ServerResource($server);
    }
}
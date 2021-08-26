<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\Server;

class ServerEndpoint extends Endpoint
{
    public function get(int $id)
    {
        $server = $this->getRequest("servers/{$id}");

        return new Server($server);
    }
}
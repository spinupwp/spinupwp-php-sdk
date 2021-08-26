<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

class ServerEndpoint extends Endpoint
{
    public function get(int $id)
    {
        $server = $this->getRequest("servers/{$id}");

        return $server;
    }
}
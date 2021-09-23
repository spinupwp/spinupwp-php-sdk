<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\ResourceCollection;
use DeliciousBrains\SpinupWp\Resources\Server as ServerResource;

class Server extends Endpoint
{
    public function all(int $page = 1): ResourceCollection
    {
        $servers = $this->getRequest("servers?page={$page}");

        return $this->transformCollection($servers, ServerResource::class);
    }

    public function get(int $id): ServerResource
    {
        $server = $this->getRequest("servers/{$id}");

        return new ServerResource($server['data'], $this);
    }
}
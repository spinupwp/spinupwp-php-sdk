<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\ResourceCollection;
use DeliciousBrains\SpinupWp\Resources\Server as ServerResource;

class Server extends Endpoint
{
    public function list(int $page = 1): ResourceCollection
    {
        $servers = $this->getRequest("servers?page={$page}");

        return $this->transformCollection($servers, ServerResource::class, $page);
    }

    public function get(int $id): ServerResource
    {
        $server = $this->getRequest("servers/{$id}");

        return new ServerResource($server, $this->spinupwp);
    }

    public function reboot(int $id): int
    {
        $request = $this->postRequest("servers/{$id}/reboot");

        return $request['event_id'];
    }

    public function restartNginx(int $id): int
    {
        $request = $this->postRequest("servers/{$id}/services/nginx/restart");

        return $request['event_id'];
    }

    public function restartPhp(int $id): int
    {
        $request = $this->postRequest("servers/{$id}/services/php/restart");

        return $request['event_id'];
    }
}

<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\ResourceCollection;
use DeliciousBrains\SpinupWp\Resources\Site as SiteResource;

class Site extends Endpoint
{
    public function list(int $page = 1): ResourceCollection
    {
        $sites = $this->getRequest("sites?page={$page}");

        return $this->transformCollection($sites, SiteResource::class, $page);
    }

    public function listForServer(int $serverId, int $page = 1): ResourceCollection
    {
        $sites = $this->getRequest("sites?server_id={$serverId}&page={$page}");

        return $this->transformCollection($sites, SiteResource::class, $page);
    }

    public function get(int $id): SiteResource
    {
        $site = $this->getRequest("sites/{$id}");

        return new SiteResource($site, $this->spinupwp);
    }

    public function create(int $serverId, array $data, bool $wait = false): SiteResource
    {
        $site = $this->postRequest('sites', array_merge($data, [
            'server_id' => $serverId,
        ]));

        if ($wait) {
            return $this->wait(function () use ($site) {
                $event = $this->spinupwp->events->get($site['event_id']);

                if (!in_array($event->status, ['deployed', 'failed'])) {
                    return false;
                }

                return $this->get($site['data']['id']);
            });
        }

        return new SiteResource($site, $this->spinupwp);
    }

    public function delete(int $id): int
    {
        $request = $this->deleteRequest("sites/{$id}");

        return $request['event_id'];
    }

    public function gitDeploy(int $id): int
    {
        $request = $this->postRequest("sites/{$id}/git/deploy");

        return $request['event_id'];
    }

    public function purgePageCache(int $id): int
    {
        $request = $this->postRequest("sites/{$id}/page-cache/purge");

        return $request['event_id'];
    }
}

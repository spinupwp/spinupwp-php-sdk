<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\Event as EventResource;
use DeliciousBrains\SpinupWp\Resources\ResourceCollection;
use DeliciousBrains\SpinupWp\Resources\Site as SiteResource;

class Site extends Endpoint
{
    public function list(int $page = 1): ResourceCollection
    {
        $sites = $this->getRequest("sites?page={$page}");

        return $this->transformCollection($sites, SiteResource::class, $page);
    }

    public function get(int $id): SiteResource
    {
        $site = $this->getRequest("sites/{$id}");

        return new SiteResource($site['data'], $this->spinupwp);
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

                $site = $this->get($site['data']['id']);
                $site->event = $event;
                return $site;
            });
        }

        return new SiteResource($site, $this->spinupwp);
    }

    public function delete(int $id): int
    {
        $request = $this->deleteRequest("sites/{$id}");

        return $request['event_id'];
    }
}

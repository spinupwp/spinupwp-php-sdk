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

    public function get(int $id): SiteResource
    {
        $site = $this->getRequest("sites/{$id}");

        return new SiteResource($site['data'], $this);
    }

    public function create(int $serverId, array $data, bool $wait = false): SiteResource
    {
        $site = $this->postRequest('sites', array_merge($data, [
            'server_id' => $serverId,
        ]));

        if ($wait) {
            return $this->wait(function () use ($site) {
                $event = (new Event($this->client))->get($site['event_id']);

                if (!in_array($event->status, ['deployed', 'failed'])) {
                    return false;
                }

                return $this->get($site['data']['id']);
            });
        }

        return new SiteResource($site['data'], $this);
    }

    public function delete(int $id): void
    {
        $this->deleteRequest("sites/{$id}");
    }
}
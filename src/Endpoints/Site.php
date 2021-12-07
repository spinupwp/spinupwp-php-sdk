<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\ResourceCollection;
use DeliciousBrains\SpinupWp\Resources\Site as SiteResource;

class Site extends Endpoint
{
    public function list(int $page = 1, array $parameters = []): ResourceCollection
    {
        $sites = $this->getRequest('sites', array_merge([
            'page' => $page,
        ], $parameters));

        return $this->transformCollection(
            $sites['data'],
            SiteResource::class,
            $this->getPaginator($sites['pagination'], $parameters),
        );
    }

    public function listForServer(int $serverId, int $page = 1, array $parameters = []): ResourceCollection
    {
        return $this->list($page, array_merge([
            'server_id' => $serverId,
        ], $parameters));
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

    public function delete(int $id, bool $deleteDatabase = false, bool $deleteBackups = false): int
    {
        $request = $this->deleteRequest("sites/{$id}", [
            'delete_database' => $deleteDatabase,
            'delete_backups'  => $deleteBackups,
        ]);

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

    public function purgeObjectCache(int $id): int
    {
        $request = $this->postRequest("sites/{$id}/object-cache/purge");

        return $request['event_id'];
    }

    public function correctFilePermissions(int $id): int
    {
        $request = $this->postRequest("sites/{$id}/file-permissions/correct");

        return $request['event_id'];
    }
}

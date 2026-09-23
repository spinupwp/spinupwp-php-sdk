<?php

namespace SpinupWp\Endpoints;

use SpinupWp\Resources\ResourceCollection;
use SpinupWp\Resources\Site as SiteResource;

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

    public function disable(int $id): int
    {
        $request = $this->postRequest("sites/{$id}/disable");

        return $request['event_id'];
    }

    public function enable(int $id): int
    {
        $request = $this->postRequest("sites/{$id}/enable");

        return $request['event_id'];
    }

    public function enableHttps(int $id, array $data): int
    {
        $request = $this->postRequest("sites/{$id}/https", $data);

        return $request['event_id'];
    }

    public function updateHttps(int $id, array $data): int
    {
        $request = $this->putRequest("sites/{$id}/https", $data);

        return $request['event_id'];
    }

    public function disableHttps(int $id): int
    {
        $request = $this->deleteRequest("sites/{$id}/https");

        return $request['event_id'];
    }

    public function updatePhpSettings(int $id, array $data): int
    {
        $request = $this->putRequest("sites/{$id}/php", $data);

        return $request['event_id'];
    }

    public function enableSpinupwpSubdomain(int $id): int
    {
        $request = $this->postRequest("sites/{$id}/spinupwp-subdomain");

        return $request['event_id'];
    }

    public function disableSpinupwpSubdomain(int $id): int
    {
        $request = $this->deleteRequest("sites/{$id}/spinupwp-subdomain");

        return $request['event_id'];
    }

    public function listDomains(int $id): array
    {
        $domains = $this->getRequest("sites/{$id}/domains");

        return $domains['data'];
    }

    public function addDomain(int $id, array $data): array
    {
        $domain = $this->postRequest("sites/{$id}/domains", $data);

        return $domain;
    }

    public function updateDomain(int $siteId, int $domainId, array $data): array
    {
        $domain = $this->putRequest("sites/{$siteId}/domains/{$domainId}", $data);

        return $domain;
    }

    public function deleteDomain(int $siteId, int $domainId): int
    {
        $request = $this->deleteRequest("sites/{$siteId}/domains/{$domainId}");

        return $request['event_id'];
    }
}

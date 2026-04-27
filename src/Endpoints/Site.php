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

    public function connectGit(int $id, array $data): int
    {
        $request = $this->postRequest("sites/{$id}/git", $data);

        return $request['event_id'];
    }

    public function updateGit(int $id, array $data): int
    {
        $request = $this->patchRequest("sites/{$id}/git", $data);

        return $request['event_id'];
    }

    public function disconnectGit(int $id): int
    {
        $request = $this->deleteRequest("sites/{$id}/git");

        return $request['event_id'];
    }

    public function enablePageCache(int $id, array $data = []): int
    {
        $request = $this->postRequest("sites/{$id}/page-cache", $data);

        return $request['event_id'];
    }

    public function updatePageCache(int $id, array $data): int
    {
        $request = $this->patchRequest("sites/{$id}/page-cache", $data);

        return $request['event_id'];
    }

    public function disablePageCache(int $id): int
    {
        $request = $this->deleteRequest("sites/{$id}/page-cache");

        return $request['event_id'];
    }

    public function updateNginx(int $id, array $data): SiteResource
    {
        $site = $this->patchRequest("sites/{$id}/nginx", $data);

        return new SiteResource($site, $this->spinupwp);
    }

    public function enableCron(int $id, array $data): int
    {
        $request = $this->postRequest("sites/{$id}/cron", $data);

        return $request['event_id'];
    }

    public function updateCron(int $id, array $data): int
    {
        $request = $this->putRequest("sites/{$id}/cron", $data);

        return $request['event_id'];
    }

    public function disableCron(int $id): int
    {
        $request = $this->deleteRequest("sites/{$id}/cron");

        return $request['event_id'];
    }

    public function enableBasicAuth(int $id, array $data): int
    {
        $request = $this->postRequest("sites/{$id}/basic-auth", $data);

        return $request['event_id'];
    }

    public function updateBasicAuth(int $id, array $data): int
    {
        $request = $this->patchRequest("sites/{$id}/basic-auth", $data);

        return $request['event_id'];
    }

    public function disableBasicAuth(int $id): int
    {
        $request = $this->deleteRequest("sites/{$id}/basic-auth");

        return $request['event_id'];
    }

    public function listPathRedirects(int $id): array
    {
        $redirects = $this->getRequest("sites/{$id}/path-redirects");

        return $redirects['data'];
    }

    public function addPathRedirect(int $id, array $data): int
    {
        $request = $this->postRequest("sites/{$id}/path-redirects", $data);

        return $request['event_id'];
    }

    public function deletePathRedirect(int $siteId, array $data): int
    {
        $request = $this->deleteRequest("sites/{$siteId}/path-redirects", $data);

        return $request['event_id'];
    }

    public function updateBackupSettings(int $id, array $data): SiteResource
    {
        $site = $this->patchRequest("sites/{$id}/backup-settings", $data);

        return new SiteResource($site, $this->spinupwp);
    }

    public function updateBackupSchedule(int $id, array $data): SiteResource
    {
        $site = $this->patchRequest("sites/{$id}/backup-schedule", $data);

        return new SiteResource($site, $this->spinupwp);
    }

    public function updateSiteUser(int $id, array $data): int
    {
        $request = $this->putRequest("sites/{$id}/site-user", $data);

        return $request['event_id'];
    }
}

<?php

namespace SpinupWp\Resources;

class Site extends Resource
{
    public function delete(bool $deleteDatabase = false, bool $deleteBackups = false): int
    {
        return $this->spinupwp->sites->delete(
            $this->id,
            $deleteDatabase,
            $deleteBackups,
        );
    }

    public function gitDeploy(): int
    {
        return $this->spinupwp->sites->gitDeploy($this->id);
    }

    public function purgePageCache(): int
    {
        return $this->spinupwp->sites->purgePageCache($this->id);
    }

    public function purgeObjectCache(): int
    {
        return $this->spinupwp->sites->purgeObjectCache($this->id);
    }

    public function correctFilePermissions(): int
    {
        return $this->spinupwp->sites->correctFilePermissions($this->id);
    }

    public function enableHttps(array $data): int
    {
        return $this->spinupwp->sites->enableHttps($this->id, $data);
    }

    public function updateHttps(array $data): int
    {
        return $this->spinupwp->sites->updateHttps($this->id, $data);
    }

    public function disableHttps(): int
    {
        return $this->spinupwp->sites->disableHttps($this->id);
    }

    public function updatePhpSettings(array $data): int
    {
        return $this->spinupwp->sites->updatePhpSettings($this->id, $data);
    }

    public function enableSpinupwpSubdomain(): int
    {
        return $this->spinupwp->sites->enableSpinupwpSubdomain($this->id);
    }

    public function disableSpinupwpSubdomain(): int
    {
        return $this->spinupwp->sites->disableSpinupwpSubdomain($this->id);
    }

    public function listDomains(): array
    {
        return $this->spinupwp->sites->listDomains($this->id);
    }

    public function addDomain(array $data): array
    {
        return $this->spinupwp->sites->addDomain($this->id, $data);
    }

    public function updateDomain(int $domainId, array $data): array
    {
        return $this->spinupwp->sites->updateDomain($this->id, $domainId, $data);
    }

    public function deleteDomain(int $domainId): int
    {
        return $this->spinupwp->sites->deleteDomain($this->id, $domainId);
    }

    public function connectGit(array $data): int
    {
        return $this->spinupwp->sites->connectGit($this->id, $data);
    }

    public function updateGit(array $data): ?int
    {
        return $this->spinupwp->sites->updateGit($this->id, $data);
    }

    public function disconnectGit(): int
    {
        return $this->spinupwp->sites->disconnectGit($this->id);
    }

    public function enablePageCache(array $data = []): int
    {
        return $this->spinupwp->sites->enablePageCache($this->id, $data);
    }

    public function updatePageCache(array $data): int
    {
        return $this->spinupwp->sites->updatePageCache($this->id, $data);
    }

    public function disablePageCache(): int
    {
        return $this->spinupwp->sites->disablePageCache($this->id);
    }

    public function updateNginx(array $data): array
    {
        return $this->spinupwp->sites->updateNginx($this->id, $data);
    }

    public function enableCron(array $data): int
    {
        return $this->spinupwp->sites->enableCron($this->id, $data);
    }

    public function updateCron(array $data): int
    {
        return $this->spinupwp->sites->updateCron($this->id, $data);
    }

    public function disableCron(): int
    {
        return $this->spinupwp->sites->disableCron($this->id);
    }

    public function enableBasicAuth(array $data): int
    {
        return $this->spinupwp->sites->enableBasicAuth($this->id, $data);
    }

    public function updateBasicAuth(array $data): int
    {
        return $this->spinupwp->sites->updateBasicAuth($this->id, $data);
    }

    public function disableBasicAuth(): int
    {
        return $this->spinupwp->sites->disableBasicAuth($this->id);
    }

    public function listPathRedirects(): array
    {
        return $this->spinupwp->sites->listPathRedirects($this->id);
    }

    public function addPathRedirect(array $data): int
    {
        return $this->spinupwp->sites->addPathRedirect($this->id, $data);
    }

    public function deletePathRedirect(array $data): int
    {
        return $this->spinupwp->sites->deletePathRedirect($this->id, $data);
    }

    public function updateBackupSettings(array $data): self
    {
        return $this->spinupwp->sites->updateBackupSettings($this->id, $data);
    }

    public function updateBackupSchedule(array $data): self
    {
        return $this->spinupwp->sites->updateBackupSchedule($this->id, $data);
    }

    public function updateSiteUser(array $data): int
    {
        return $this->spinupwp->sites->updateSiteUser($this->id, $data);
    }
}

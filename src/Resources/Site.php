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
}

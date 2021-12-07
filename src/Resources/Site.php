<?php

namespace DeliciousBrains\SpinupWp\Resources;

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
}

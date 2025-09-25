<?php

namespace SpinupWp\Resources;

class Server extends Resource
{
    public function sites(): ResourceCollection
    {
        return $this->spinupwp->sites->listForServer($this->id);
    }

    public function delete(bool $deleteOnProvider = false): int
    {
        return $this->spinupwp->servers->delete($this->id, $deleteOnProvider);
    }

    public function reboot(): int
    {
        return $this->spinupwp->servers->reboot($this->id);
    }

    public function restartNginx(): int
    {
        return $this->spinupwp->servers->restartNginx($this->id);
    }

    public function restartRedis(): int
    {
        return $this->spinupwp->servers->restartRedis($this->id);
    }

    public function restartPhp(): int
    {
        return $this->spinupwp->servers->restartPhp($this->id);
    }

    public function restartMysql(): int
    {
        return $this->spinupwp->servers->restartMysql($this->id);
    }
}

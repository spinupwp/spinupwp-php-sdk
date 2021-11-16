<?php

namespace DeliciousBrains\SpinupWp\Resources;

class Server extends Resource
{
    public function sites(): ResourceCollection
    {
        return $this->spinupwp->sites->listForServer($this->id);
    }

    public function reboot(): int
    {
        return $this->spinupwp->servers->reboot($this->id);
    }

    public function restartNginx(): int
    {
        return $this->spinupwp->servers->restartNginx($this->id);
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

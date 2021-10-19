<?php

namespace DeliciousBrains\SpinupWp\Resources;

class Server extends Resource
{
    public function sites(): ResourceCollection
    {
        return $this->spinupwp->sites->listForServer($this->id);
    }
}

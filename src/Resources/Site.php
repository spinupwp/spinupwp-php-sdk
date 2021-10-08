<?php

namespace DeliciousBrains\SpinupWp\Resources;

use DeliciousBrains\SpinupWp\Resources\Event as EventResource;

class Site extends Resource
{
    public function delete(): ?int
    {
        return $this->spinupwp->sites->delete($this->id);
    }
}

<?php

namespace DeliciousBrains\SpinupWp\Resources;

use DeliciousBrains\SpinupWp\SpinupWp;

class Site extends Resource
{
    public function __construct(array $site, SpinupWp $spinupwp)
    {
        parent::__construct($site, $spinupwp);
        $this->eventId = $site['event_id'] ?? null;
    }

    public function delete(): ?int
    {
        return $this->spinupwp->sites->delete($this->id);
    }
}

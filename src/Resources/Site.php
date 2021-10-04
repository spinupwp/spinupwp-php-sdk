<?php

namespace DeliciousBrains\SpinupWp\Resources;

use DeliciousBrains\SpinupWp\Traits\HasEvent;
use DeliciousBrains\SpinupWp\Resources\Event as EventResource;

class Site extends Resource
{
    use HasEvent;

    public function delete(): EventResource
    {
        return $this->endpoint->delete($this->id);
    }
}
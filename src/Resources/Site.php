<?php

namespace DeliciousBrains\SpinupWp\Resources;

use DeliciousBrains\SpinupWp\Resources\Event as EventResource;

class Site extends Resource
{
    public function delete(): EventResource
    {
        return $this->endpoint->delete($this->id);
    }
}
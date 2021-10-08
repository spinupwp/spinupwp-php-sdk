<?php

namespace DeliciousBrains\SpinupWp\Resources;

use DeliciousBrains\SpinupWp\Resources\Event as EventResource;

class Site extends Resource
{
    public function delete(): ?EventResource
    {
        if (method_exists($this->endpoint, 'delete')) {
            return $this->endpoint->delete($this->id);
        }
        return null;
    }
}

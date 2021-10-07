<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\Event as EventResource;

class Event extends Endpoint
{
    public function get(int $id): EventResource
    {
        $event = $this->getRequest("events/{$id}");

        return new EventResource($event['data'], $this);
    }
}

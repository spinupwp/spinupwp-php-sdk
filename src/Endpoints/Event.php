<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\Event as EventResource;
use DeliciousBrains\SpinupWp\Resources\ResourceCollection;

class Event extends Endpoint
{
    public function list(int $page = 1): ResourceCollection
    {
        $events = $this->getRequest("events?page={$page}");

        return $this->transformCollection($events, EventResource::class, $page);
    }

    public function get(int $id): EventResource
    {
        $event = $this->getRequest("events/{$id}");

        return new EventResource($event, $this->spinupwp);
    }
}

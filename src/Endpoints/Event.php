<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Resources\Event as EventResource;
use DeliciousBrains\SpinupWp\Resources\ResourceCollection;

class Event extends Endpoint
{
    public function list(int $page = 1, array $parameters = []): ResourceCollection
    {
        $events = $this->getRequest('events', array_merge([
            'page' => $page,
        ], $parameters));

        return $this->transformCollection(
            $events['data'],
            EventResource::class,
            $this->getPaginator($events['pagination'], $parameters),
        );
    }

    public function get(int $id): EventResource
    {
        $event = $this->getRequest("events/{$id}");

        return new EventResource($event, $this->spinupwp);
    }
}

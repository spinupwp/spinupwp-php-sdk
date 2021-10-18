<?php

namespace DeliciousBrains\SpinupWp\Resources;

use DeliciousBrains\SpinupWp\SpinupWp;

class Resource
{
    protected array $attributes;

    protected ?int $eventId = null;

    protected SpinupWp $spinupwp;

    public function __construct(array $payload, SpinupWp $spinupwp)
    {
        $this->attributes = $payload['data'] ?? [];
        $this->eventId    = $payload['event_id'] ?? null;
        $this->spinupwp   = $spinupwp;

        $this->fill();
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    protected function fill(): void
    {
        foreach ($this->attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function eventId(): ?int
    {
        return $this->eventId;
    }

    public function event(): ?Event
    {
        if (!$this->eventId) {
            return null;
        }

        return $this->spinupwp->events->get($this->eventId);
    }
}

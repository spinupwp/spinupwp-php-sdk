<?php

namespace SpinupWp\Resources;

use SpinupWp\SpinupWp;

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
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
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

<?php

namespace DeliciousBrains\SpinupWp\Resources;

use DeliciousBrains\SpinupWp\SpinupWp;

abstract class Resource
{
    protected array $attributes;

    protected SpinupWp $spinupwp;

    public int $event_id = 0;

    public function __construct(array $attributes, SpinupWp $spinupwp)
    {
        $this->attributes = $attributes;
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

    public function event()
    {
        if (!$this->event_id) {
            return null;
        }

        return $this->spinupwp->events->get($this->event_id);
    }
}

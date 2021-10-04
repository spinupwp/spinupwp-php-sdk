<?php

namespace DeliciousBrains\SpinupWp\Traits;

trait HasEvent {
    public ?int $event_id = 0;

    public function event()
    {
        if(!$this->event_id){
            return null;
        }

        return $this->spinupwp->events->get($this->event_id);
    }
}
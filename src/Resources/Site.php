<?php

namespace DeliciousBrains\SpinupWp\Resources;

class Site extends Resource
{
    public function delete(): ?int
    {
        if (method_exists($this->endpoint, 'delete')) {
            return $this->endpoint->delete($this->id);
        }
        return null;
    }
}

<?php

namespace DeliciousBrains\SpinupWp\Resources;

class Site extends Resource
{
    public function delete()
    {
        $this->endpoint->delete($this->id);
    }
}
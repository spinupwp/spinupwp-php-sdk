<?php

namespace DeliciousBrains\SpinupWp\Resources;

class Site extends Resource
{
    public function delete(): ?int
    {
        return $this->spinupwp->sites->delete($this->id);
    }
}

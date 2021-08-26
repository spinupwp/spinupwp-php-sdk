<?php

namespace DeliciousBrains\SpinupWp\Resources;

abstract class Resource
{
    protected array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes['data'];

        $this->fill();
    }

    protected function fill()
    {
        foreach ($this->attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
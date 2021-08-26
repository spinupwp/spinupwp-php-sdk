<?php

namespace DeliciousBrains\SpinupWp\Resources;

use DeliciousBrains\SpinupWp\Endpoints\Endpoint;

abstract class Resource
{
    protected array $attributes;

    protected Endpoint $endpoint;

    public function __construct(array $attributes, Endpoint $endpoint)
    {
        $this->attributes = $attributes['data'];
        $this->endpoint   = $endpoint;

        $this->fill();
    }

    protected function fill()
    {
        foreach ($this->attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }
}